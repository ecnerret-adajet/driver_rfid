<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;
use App\Cardholder;
use App\Pickup;
use App\Log;
use Flashy;

class PickupOnlineController extends Controller
{
    public function index() 
    {
        return view('pickups.pickupIndex');
    }

    public function getPickupData()
    {
        $pickups = Pickup::whereHas('user', function($q) {
                            $q->where('company_id', Auth::user()->company_id);
                        })
                        ->orderBy('created_at','DESC')
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
                        ->with('cardholder','user')
                        ->get();
        
        return $pickups;
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
            'do_number' => 'required'
        ]);

        $pickup = Auth::user()->pickups()->create($request->all());

        flashy()->success('Pickup has successfully created!');
        return redirect('pickups/online');
    }
}
