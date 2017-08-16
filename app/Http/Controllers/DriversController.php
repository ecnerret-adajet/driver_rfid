<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ConfirmDriver;
use Carbon\Carbon;
use App\Clasification;
use App\Cardholder;
use App\Hauler;
use App\Driver;
use App\Truck;
use App\User;
use App\Binder;
use App\Card;
use Toast;
use App\Setting;

class DriversController extends Controller
{
    /**
     * Display a listing of the resource.
     * * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drivers = Driver::all();
        return view('drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clasifications = Clasification::pluck('name','id');
        $haulers = ['' => ''] + Hauler::pluck('name','id')->all();
        $trucks = ['' => ''] + Truck::pluck('plate_number','id')->all();
        $cards = ['' => ''] + Card::pluck('CardNo','CardID')->all();

        // for testing
        // $rfid_card = Card::whereHas('binders', function($q) {
        //     $q->where('card_id','1');
        // })->pluck('CardNo','CardID');

        return view('drivers.create',compact('clasifications','haulers','trucks','cards'));
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
            // 'avatar' => 'required',
            'name' => 'required|max:255|unique:drivers',
            'driver_number' => 'required|integer|unique:drivers',
            'card_list' => 'required',
            'hauler_list' => 'required',
            'truck_list' => 'required',
            'phone_number' => 'required',
                
        ]);

        $card_rfid = $request->input('card_list');
        $driver = Auth::user()->drivers()->create($request->all());
        if($request->hasFile('avatar')){
            $driver->avatar = $request->file('avatar')->store('drivers');
        } 
        $driver->print_status = 1;
        $driver->card()->associate($card_rfid);
        $driver->save();

        $driver->haulers()->attach($request->input('hauler_list'));
        $driver->trucks()->attach($request->input('truck_list'));

        //send email to supervisor for approval
        $setting = Setting::first();
        Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmDriver($driver));
        
        toast()->success('message', 'title');
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
    public function edit(Driver $driver)
    {
        $clasifications = Clasification::pluck('name','id');
        $haulers = Hauler::pluck('name','id');
        $trucks = Truck::pluck('plate_number','id');
        $cards = ['' => ''] + Card::pluck('CardNo','CardID')->all();
        
        return view('drivers.edit',compact(
            'driver',
            'clasifications',
            'haulers',
            'cards',
            'trucks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Driver $driver)
    {
        $this->validate($request, [
                'name' => 'required',
                'hauler_list' => 'required',
                'truck_list' => 'required',
                'phone_number' => 'required',
                'card_list' => 'required',
                'clasification_list' => 'required',
        ]);

        $card_rfid = $request->input('card_list');
        $clasification_id = $request->input('clasification_list');

        $driver->update($request->all());
        if($request->hasFile('avatar')){
            $driver->avatar = $request->file('avatar')->store('drivers');
        }        

        if(empty($driver->update_count)) {
            $driver->update_count = 1;
        } else {
            $driver->update_count += 1;
        }
        
        $driver->print_status = 1;
        $driver->card()->associate($card_rfid);
        $driver->clasification()->associate($clasification_id);
        $driver->save();

        $driver->haulers()->sync( (array) $request->input('hauler_list'));
        $driver->trucks()->sync( (array) $request->input('truck_list'));

       
        //send email to supervisor for approval
        $setting = Setting::first();
        Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmDriver($driver));


        return redirect('drivers');
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
