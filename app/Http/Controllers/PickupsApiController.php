<?php

namespace App\Http\Controllers;

use App\Transformers\PickupAPITransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Cardholder;
use Carbon\Carbon;
use App\Pickup;
use App\Log;
use Flashy;

class PickupsApiController extends Controller
{
    public function unserved()
    {
        $unserved = Pickup::whereNull('cardholder_id')
                        ->where('created_at', '>=', Carbon::now()->subDays(30))
                        ->orderBy('created_at','DESC')
                        ->get();
                        // ->paginate(10);

        $manager = new Manager();
        $resource = new Collection($unserved, new PickupAPITransformer());
        return $manager->createData($resource)->toArray();

        // return response()->json($unserved, 200);
    }

    public function assigned()
    {
        $assigned = Pickup::whereDate('activation_date', Carbon::today())
                        ->whereNull('deactivated_date')
                        ->whereNotNull('cardholder_id')
                        ->orderBy('id','DESC')
                        // ->take(8)
                        ->get();
                        // ->paginate(10);

        $manager = new Manager();
        $resource = new Collection($assigned, new PickupAPITransformer());
        return $manager->createData($resource)->toArray();

        // return $assigned;
    }

    public function served()
    {
        $served = Pickup::whereDate('deactivated_date', Carbon::today())
                    ->whereNotNull('deactivated_date')
                    ->whereNotNull('cardholder_id')
                    ->orderBy('created_at','DESC')
                    // ->take(1)
                    ->get();
                    // ->paginate(10);

        $manager = new Manager();
        $resource = new Collection($served, new PickupAPITransformer());
        return $manager->createData($resource)->toArray();

        // return $served;
    }

    /**
     * Deactivate a given pickup entry
     *
     * @param [type] $id
     * @return void
     */
    public function pickupDeactivate(Pickup $pickup)
     {
         $pickup->update([
            'availability' => false,
            'deactivated_date' => Carbon::now()
         ]);

        // Record Log Activity
        $activity = activity()
        ->performedOn($pickup)
        ->withProperties(['cardholder' => $pickup->cardholder_id])
        ->log('Deactivated Pickup RFID');

         return $pickup;
     }

     public function cardholderAvailability()
     {
         $guard_cards = Cardholder::select('CardholderID')
            ->where('Name', 'LIKE', '%PICKUP CONFIRM%')
            ->pluck('CardholderID');

        $pickup_cards = Pickup::select('cardholder_id')
                            ->whereNotNull('cardholder_id')
                            ->where('availability',1)->get();

        $cardholders = Cardholder::whereNotIn('CardholderID', $pickup_cards)
                                    ->whereNotIn('CardholderID', $guard_cards)
                                    ->where('Name', 'LIKE', '%Pickup%')
                                    ->get();

        return $cardholders;
     }

    public function assignCardholder(Request $request, Pickup $pickup)
    {

        $this->validate($request, [
            'cardholder_list' => 'required'
        ]);

        // Set the cardholder value
        $plate = $request->input('cardholder_list');

        // Assign rfid to pickup record
        $pickup->cardholder()->associate($plate);
        $pickup->activation_date = Carbon::now();
        $pickup->save();

        // Record Log Activity
        $activity = activity()
        ->performedOn($pickup)
        ->withProperties(['cardholder' => $plate])
        ->log('Assigned Pickup RFID');

        return $pickup;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
