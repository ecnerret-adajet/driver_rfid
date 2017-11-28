<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ConfirmLostCard;
use Spatie\Activitylog\Models\Activity;
use Flashy;
use App\Lost;
use App\Driver;
use App\Card;
use App\Driverversion;
use App\Setting;
use App\User;
use App\Cardholder;
use DB;

class LostCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show all cardholder should not be displayed on card list 
     */
    public function removedCardholder()
    {
        $pickup_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%pickup%')
        ->pluck('CardholderID'); 

        $guard_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%GUARD%')
        ->pluck('CardholderID'); 

        $executive_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%EXECUTIVE%')
        ->pluck('CardholderID'); 

        $driver_card = Driver::select('cardholder_id')
        ->where('availability',1)
        ->pluck('cardholder_id');

        // Remove all cardholder without driver assigned
        $not_driver = array_collapse([$pickup_cards, $guard_cards, $executive_cards, $driver_card]);

        return $not_driver;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($driver)
    {
        $driver = Driver::findOrFail($driver);

        $driver_card = Driver::select('cardholder_id')->where('availability',1)->get();
        
        $cards = Card::orderBy('CardholderID','DESC')
                ->whereNotIn('CardholderID', $this->removedCardholder())
                ->where('AccessgroupID', 1) // card type
                ->where('CardholderID','>=', 15)
                ->where('CardholderID','!=', 0)
                ->get()
                ->pluck('full_deploy','CardID');

        return view('losts.create', compact('driver_card','cards','driver'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $driver)
    {
        $this->validate($request, [
            'reason' => 'required',
            'card_list' => 'required'
        ]);

        $driver = Driver::findOrFail($driver);
        $card_rfid = $request->input('card_list');

        // Lost Model
        $lost = new Lost;
        $lost->reason = $request->input('reason');
        $lost->driver()->associate($driver->id);
        $lost->user()->associate(Auth::user()->id);
        $lost->save();

        // Driver Revision
        $version =  new Driverversion;
        $version->driver_id = $driver->id;
        $version->card_no = $driver->card_id;
        $version->cardholder_id = $driver->cardholder_id;
        $version->user_id = Auth::user()->id;
        $version->plate_number = $driver->truck->plate_number;
        $version->vendor = $driver->hauler->name;
        $version->start_date = $driver->start_validity_date;
        $version->end_date = $driver->end_validity_date;
        $version->save();

        // Driver Model
        $driver->print_status = 1;
        $driver->availability = 0;
        $driver->notif_status = 1;
        $driver->card()->associate($card_rfid);
        $driver->cardholder()->associate($driver->card->CardholderID);    
        $driver->save();

        //Deactivating RFID card from ASManager
        if(!empty($driver->card_id)) {
            $card = Card::where('CardID',$driver->card_id)->first();
            $card->CardStatus = 1; 
        }

        // Records to system log
        $activity = activity()
        ->performedOn($driver)
        ->withProperties(['card_no' => $driver->card_id])
        ->log('Reprint Card');

        //send email to supervisor for approval
        $setting = Setting::first();
        Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmLostCard($driver, $lost));

        flashy()->success('Driver has successfully requested for lost card!');
        return redirect('drivers');

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
