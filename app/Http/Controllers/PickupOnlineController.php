<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;
use App\Cardholder;
use App\Pickup;
use App\ModuleSetting;
use App\Log;
use Flashy;

use App\SapDoNumber;
use App\CoaEmailReceiver;
use Illuminate\Support\Facades\Mail;
use App\Mail\CoaEmailNotification;

use App\User;
use App\SapUser;
use App\SapServer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;

class PickupOnlineController extends Controller
{
    public function index()
    {
        $allowedPickup = collect(json_decode(ModuleSetting::where('modelable_type','App\Pickup')->first()->modelable_array,true));

        $isAllowed = in_array(Auth::user()->company->id, $allowedPickup->toArray()) ? 'true' : 'false';

        // For convan entries // karl agaua
        $isDoExcempted = in_array(Auth::user()->id, collect([1, 1030, 1054])->toArray()) ? 'true' : 'false';

        $userCompanyName = Auth::user()->company->name;

        $lfug_customer_code = Auth::user()->lfug_customer_code;
        $pfmc_customer_code = Auth::user()->pfmc_customer_code;
        $customer_codes = [];
        if ($lfug_customer_code || $pfmc_customer_code) {
            if ($lfug_customer_code) {
                $lfug_customer_code = explode(',', $lfug_customer_code);
            } else {
                $lfug_customer_code = [];
            }
            if ($pfmc_customer_code) {
                $pfmc_customer_code = explode(',', $pfmc_customer_code);
            } else {
                $pfmc_customer_code = [];
            }
            $customer_codes = array_merge($lfug_customer_code, $pfmc_customer_code);
        }

        $sap_do_numbers = SapDoNumber::select('do_number')
            ->where('status', '=', 'A')
            ->whereIn('customer_code', $customer_codes)
            ->get();

        if ($sap_do_numbers) {
            if ($sap_do_numbers) {
                $do_numbers = [];
                foreach ($sap_do_numbers as $k => $do_number) {
                    array_push($do_numbers, $do_number['do_number']);
                }
            }
        }

        $lfug_customer_code = Auth::user()->lfug_customer_code;
        $pfmc_customer_code = Auth::user()->pfmc_customer_code;
        $customer_code = '';
        if ($lfug_customer_code || $pfmc_customer_code) {
            $customer_code = $lfug_customer_code . '/' . $pfmc_customer_code;
        } else {
            $customer_code = 'empty';
        }

        return view('pickups.pickupIndex', compact('userCompanyName', 'isAllowed', 'do_numbers', 'customer_code', 'isDoExcempted'));
    }

    public function getPickupData()
    {
        $pickups = Pickup::whereHas('user', function ($q) {
            $q->where('company_id', Auth::user()->company_id);
        })
        ->orderBy('created_at', 'DESC')
        // ->where('bypass_rfid', false)
        ->whereNull('cardholder_id')
        ->where(function ($q) {
            $q->whereDate('pickup_date', '>=', Carbon::today()->subDays(3))
                ->orWhereDate('created_at', '>=', Carbon::today()->subDays(3));
        })
        ->with('cardholder', 'user')
        ->get();

        if ($pickups) {
            foreach ($pickups as $key => $pickup) {
                $pickups[$key] = $pickup;
                $do_numbers = explode(',', $pickup['do_number']);
                $do_number_status = [];
                $status = "";
                if ($do_numbers) {
                    $serve_count = 0;
                    $not_serve_count = 0;
                    foreach ($do_numbers as $k => $do_number) {
                        $sap_do_number = SapDoNumber::where('do_number', $do_number)->first();
                        $do_number_status[$k]['do_number'] =  $do_number;
                        if ($sap_do_number) {
                            $do_number_status[$k]['status'] = $sap_do_number['status'];
                            if ($sap_do_number['status'] == 'C') {
                                $serve_count += 1;
                            } else {
                                $not_serve_count += 1;
                            }
                        } else {
                            $do_number_status[$k]['status'] = "";
                        }
                    }

                    if ($not_serve_count > 0) {
                        $status = 'not_served';
                    } else {
                        if ($serve_count == count($do_numbers)) {
                            $status = 'served';
                        }
                    }
                }
                $pickups[$key]['pickup_status'] = $status;
                $pickups[$key]['do_numbers'] = $do_number_status;
            }
        }

        return $pickups;
    }

    public function getPickupHistory()
    {
        $pickups = Pickup::whereHas('user', function ($q) {
            $q->where('company_id', Auth::user()->company_id);
        })
            ->orderBy('created_at', 'DESC')
            ->whereNotNull('cardholder_id')
            ->whereNotNull('activation_date')
            ->whereNotNull('deactivated_date')
            ->whereDate('created_at', '>', Carbon::now()->subDays(60))
            ->with('cardholder', 'user')
            ->get();

        if ($pickups) {
            foreach ($pickups as $key => $pickup) {
                $pickups[$key] = $pickup;
                $do_numbers = explode(',', $pickup['do_number']);
                $do_number_status = [];
                $status = "";
                if ($do_numbers) {
                    $serve_count = 0;
                    $not_serve_count = 0;
                    foreach ($do_numbers as $k => $do_number) {
                        $sap_do_number = SapDoNumber::where('do_number', $do_number)->first();
                        $do_number_status[$k]['do_number'] =  $do_number;
                        if ($sap_do_number) {
                            $do_number_status[$k]['status'] = $sap_do_number['status'];
                            if ($sap_do_number['status'] == 'C') {
                                $serve_count += 1;
                            } else {
                                $not_serve_count += 1;
                            }
                        } else {
                            $do_number_status[$k]['status'] = "";
                        }
                    }

                    if ($not_serve_count > 0) {
                        $status = 'not_served';
                    } else {
                        if ($serve_count == count($do_numbers)) {
                            $status = 'served';
                        }
                    }
                }
                $pickups[$key]['pickup_status'] = $status;
                $pickups[$key]['do_numbers'] = $do_number_status;
            }
        }

        return $pickups;
    }

    public function getPickupWithCardholder()
    {
        $pickups = Pickup::whereHas('user', function ($q) {
            $q->where('company_id', Auth::user()->company_id);
        })
            ->orderBy('created_at', 'DESC')
            ->whereNotNull('cardholder_id')
            ->whereNotNull('activation_date')
            ->whereNotNull('deactivated_date')
            ->whereDate('created_at', '>', Carbon::now()->subDays(60))
            ->with('cardholder', 'user')
            ->get();

        if ($pickups) {
            foreach ($pickups as $key => $pickup) {
                $pickups[$key] = $pickup;
                $do_numbers = explode(',', $pickup['do_number']);
                $do_number_status = [];
                $status = "";
                if ($do_numbers) {
                    $serve_count = 0;
                    $not_serve_count = 0;
                    foreach ($do_numbers as $k => $do_number) {
                        $sap_do_number = SapDoNumber::where('do_number', $do_number)->first();
                        $do_number_status[$k]['do_number'] =  $do_number;
                        if ($sap_do_number) {
                            $do_number_status[$k]['status'] = $sap_do_number['status'];
                            if ($sap_do_number['status'] == 'C') {
                                $serve_count += 1;
                            } else {
                                $not_serve_count += 1;
                            }
                        } else {
                            $do_number_status[$k]['status'] = "";
                        }
                    }

                    if ($not_serve_count > 0) {
                        $status = 'not_served';
                    } else {
                        if ($serve_count == count($do_numbers)) {
                            $status = 'served';
                        }
                    }
                }
                $pickups[$key]['pickup_status'] = $status;
                $pickups[$key]['do_numbers'] = $do_number_status;
            }
        }

        return $pickups;
    }

    public function pickupServedSearch(Request $request)
    {
        $this->validate($request, [
            'search_date' => 'required',
        ]);

        $search_date = $request->get('search_date');

        $pickups = Pickup::whereHas('user', function ($q) {
            $q->where('company_id', Auth::user()->company_id);
        })
            ->orderBy('created_at', 'DESC')
            ->whereDate('deactivated_date', Carbon::parse($search_date))
            ->whereNotNull('cardholder_id')
            ->with('cardholder', 'user')
            ->get();

        return $pickups;
    }

    /**
     *  Count Number of Served / Unserved Pickups per Company / User created
     */
    public function pickupCount()
    {
        $mine_not_served = Pickup::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'DESC')
            ->whereNull('cardholder_id')
            ->with('cardholder', 'user')
            ->count();

        $mine_served = Pickup::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'DESC')
            ->whereNotNull('cardholder_id')
            ->with('cardholder', 'user')
            ->count();

        $not_yet_served = $this->getPickupData()->count();
        $served = $this->getPickupWithCardholder()->count();

        $data = array(
            'notYetServed' => $not_yet_served,
            'served' => $served,
            'mineNotServed' => $mine_not_served,
            'mineServed' => $mine_served
        );

        return $data;
    }

    public function createPickup()
    {
        return view('pickups.pickupCreate');
    }

    public function storePickup(Request $request)
    {
        $this->validate($request, [
            'plate_number' => 'required',
            'driver_name' => 'required',
            'company' => 'required',
            'do_number' => 'required',
            'coa' => 'required'
        ]);

        // $pickup = Auth::user()->pickups()->create($request->all());
        $pickup = new Pickup;
        $pickup->user_id = Auth::user()->id;
        $pickup->plate_number = $request->input('plate_number');
        $pickup->driver_name = $request->input('driver_name');
        $pickup->company = $request->input('company');
        $pickup->do_number = $request->input('do_number');
        $pickup->coa = $request->input('coa');
        $pickup->save();

        flashy()->success('Pickup has successfully created!');
        return redirect('pickups/online');
    }

    public function editPickup(Pickup $pickup)
    {
        return view('pickups.unservedEdit', compact('pickup'));
    }

    public function updatePickup(Request $request, Pickup $pickup)
    {
         $this->validate($request, [
            'plate_number' => 'required',
            'driver_name' => 'required',
            'company' => 'required',
            'do_number' => 'required',
            'remarks' => 'required'
        ]);

        $pickup->update($request->all());

        flashy()->success('Pickup has successfully update!');

        return redirect('pickups/online');

    }

    public function cancelPickup(Request $request, Pickup $pickup)
    {
        $pickup->delete();
        flashy()->success('Pickup has successfully deleted!');
        return redirect('pickups/online');
    }
}
