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
use Toast;

class DriversController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
        $haulers = Hauler::pluck('name','id');
        return view('drivers.create',compact('clasifications','haulers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $driver = Auth::user()->drivers()->create($request->all());
        if($request->hasFile('avatar')){
            $driver->avatar = $request->file('avatar')->store('drivers');
        } 
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
        $cardholders = Cardholder::pluck('Name','CardholderID');
        $clasifications = Clasification::pluck('name','id');
        $haulers = Hauler::pluck('name','id');
        $trucks = Truck::pluck('plate_number','id');
        
        return view('drivers.edit',compact(
            'driver',
            'clasifications',
            'haulers',
            'cardholders',
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
                'cardholder_list' => 'required',
                'clasification_list' => 'required',
        ]);

        $plate = $request->input('cardholder_list');
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
        $driver->cardholder()->associate($plate);
        $driver->clasification()->associate($clasification_id);
        $driver->save();

        $driver->haulers()->sync( (array) $request->input('hauler_list'));
        $driver->trucks()->sync( (array) $request->input('truck_list'));

        //send email to supervisor for approval
        Notification::send(User::first(), new ConfirmDriver($driver));


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
