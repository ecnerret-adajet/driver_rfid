<?php

namespace App\Http\Controllers\Api;

use App\Transformers\ReplacementTransformer;
use League\Fractal\Resource\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ConfirmLostCard;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Replacement;
use Carbon\Carbon;
use App\Card;
use App\Cardholder;
use App\Version;
use App\Driver;
use App\Setting;
use App\User;
use DB;

class ReplacementApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * of pending approval entries
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $replacements = Replacement::where('status',0)->orderBy('id','DESC')->get();

        $manager = new Manager();
        $resource = new Collection($replacements, new ReplacementTransformer());

        return $manager->createData($resource)->toArray();
    }

    public function approvedReplacements()
    {
        $replacements = Replacement::where('status',1)->orderBy('id','DESC')->get();

        $manager = new Manager();
        $resource = new Collection($replacements, new ReplacementTransformer());

        return $manager->createData($resource)->toArray();
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
     * Show all available RFID
     */
    public function driverRfidList()
    {
        $cards = Card::orderBy('CardholderID','DESC')
                    ->whereNotIn('CardholderID', $this->removedCardholder())
                    ->where('AccessGroupID', 1) // card type
                    ->where('CardholderID','>=', 15)
                    ->where('CardholderID','!=', 0)
                    ->get();

        return $cards;
    }

    /**
     * List all possbile reason for replacement
     *
     * @return void
     */
    public function reasonReplacement()
    {
        $reasons = array("Lost Card", "Malfunctioned", "New Driver");
        return $reasons;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'driver_id' => 'required',
            'card_id' => 'required',
            'reason_replacement' => 'required',
        ]);

        $replacement = Replacement::create([
            'user_id' => Auth::user()->id,
            'driver_id' => $request->driver_id,
            'card_id' => $request->card_id,
            'reason_replacement' => $request->reason_replacement,
            'remarks' => $request->remarks,
        ]);

        $driver = Driver::whereId($replacement->driver_id)
                        ->with('truck','hauler')
                        ->first();

        //send email to supervisor for approval
        $setting = Setting::first();
        Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmLostCard($driver));

        $manager = new Manager();
        $item = new Item($replacement, new ReplacementTransformer);

        return $manager->createData($item)->toArray()['data'];

    }

    public function forApprovalReplacements(Request $request, Replacement $replacement)
    {
        $this->validate($request, [
            'status' => 'required'
        ]);

        $replacement->status = $request->status;
        $replacement->marked_by = 1;
        $replacement->save();

        $driver = Driver::whereId($replacement->driver_id)
                        ->with('truck','hauler')
                        ->first();

        $driver->print_status = 1;
        $driver->availability = 0;
        $driver->notif_status = 1;
        $driver->card()->associate($replacement->card_id);
        $driver->cardholder()->associate($driver->card->CardholderID);
        $driver->save();

        //Deactivating RFID card from ASManager
        if(!empty($driver->card_id)) {
            $card = Card::where('CardID',$replacement->card_id)->first();
            $card->CardStatus = 1;
            $card->save();
        }

         // Records to system log
        $activity = activity()
        ->performedOn($driver)
        ->withProperties(['card_no' => $driver->card_id])
        ->log('Reprint Card');

        $manager = new Manager();
        $item = new Item($replacement, new ReplacementTransformer);

        return $manager->createData($item)->toArray()['data'];
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
