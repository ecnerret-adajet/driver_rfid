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

class PickupOnlineController extends Controller
{
    public function index() 
    {
        $allowedPickup = collect(json_decode(ModuleSetting::where('modelable_type','App\Pickup')->first()->modelable_array,true));

        $isAllowed = in_array(Auth::user()->company->id, $allowedPickup->toArray()) ? 'true' : 'false';

        $userCompanyName = Auth::user()->company->name;

        return view('pickups.pickupIndex',compact('userCompanyName','isAllowed'));
    }

    public function getPickupData()
    {
        $pickups = Pickup::whereHas('user', function($q) {
                            $q->where('company_id', Auth::user()->company_id);
                        })
                        ->orderBy('created_at','DESC')
                        ->where('bypass_rfid', false)
                        ->whereNull('cardholder_id')
                        ->with('cardholder','user')
                        ->get();

return $pickups;
    }

    public function getPickupWithCardholder()
    {
         $pickups = Pickup::whereHas('user', function($q) {
                            $q->where('company_id', Auth::user()->company_id);
                        })
                        ->orderBy('created_at','DESC')
                        ->whereNotNull('cardholder_id')
                        ->whereDate('activation_date', Carbon::today())
                        ->orWhere('bypass_rfid', true)
                        ->with('cardholder','user')
                        ->get();
        
        return $pickups;
    }

    public function pickupServedSearch(Request $request) 
    {
        $this->validate($request, [
            'search_date' => 'required',
        ]);

        $search_date = $request->get('search_date');

         $pickups = Pickup::whereHas('user', function($q) {
                            $q->where('company_id', Auth::user()->company_id);
                        })
                        ->orderBy('created_at','DESC')
                        ->whereDate('deactivated_date', Carbon::parse($search_date))
                        ->whereNotNull('cardholder_id')
                        ->with('cardholder','user')
                        ->get();
        
        return $pickups;
    }

    /**
     *  Count Number of Served / Unserved Pickups per Company / User created
     */
    public function pickupCount()
    {
        $mine_not_served = Pickup::where('user_id',Auth::user()->id)
                    ->orderBy('created_at','DESC')
                    ->whereNull('cardholder_id')
                    ->with('cardholder','user')
                    ->count();
        
        $mine_served = Pickup::where('user_id',Auth::user()->id)
            ->orderBy('created_at','DESC')
            ->whereNotNull('cardholder_id')
            ->with('cardholder','user')
            ->count();

        $not_yet_served = $this->getPickupData()->count();
        $served = $this->getPickupWithCardholder()->count();

        $data = array (
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
