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
use Excel;
use App\Log;

class DriversController extends Controller
{
    /**
     * Display a listing of the resource.
     * * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('drivers.index');
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

        $cards = ['' => ''] +  Card::pluck('CardNo','CardID')->all();

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
            'card_list' => 'required',
            'truck_list' => 'required',
            'phone_number' => 'required|max:10|min:10',
            'nbi_number' => 'required|max:8|min:8',
            'driver_license' => 'required|max:13|min:13',
                
        ],[
            'truck_list.required' => 'Plate Number is required'
        ]);

        $card_rfid = $request->input('card_list');
        $driver = Auth::user()->drivers()->create($request->all());
        if($request->hasFile('avatar')){
            $driver->avatar = $request->file('avatar')->store('drivers');
        } 
        $driver->print_status = 1;
        $driver->card()->associate($card_rfid);
        $driver->cardholder()->associate($driver->card->CardholderID);
        $driver->save();

        $driver->trucks()->attach($request->input('truck_list'));

        //send email to supervisor for approval
        $setting = Setting::first();
        Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmDriver($driver));
        
        return redirect('drivers');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        $logs = Log::with('customers')
                    ->whereNotIn('ControllerID',[1])
                    ->where('CardholderID','=',$driver->cardholder->CardholderID)
                    ->orderBy('LocalTime','DESC')
                    ->get();


        return view('drivers.show', compact('driver','logs'));
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
        
        $driver->card()->associate($card_rfid);
        $driver->clasification()->associate($clasification_id);

        if($driver->clasification_id == 2) {
            $driver->print_status = 1;

            // send email to supervisor for approval
            $setting = Setting::first();
            Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmDriver($driver));
        }

        $driver->save();

        $driver->haulers()->sync( (array) $request->input('hauler_list'));
        $driver->trucks()->sync( (array) $request->input('truck_list'));

       
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

    /*
    *
    * Export Drivers 
    *
    */
    public function exportDrivers()
    {
        $drivers = Driver::orderBy('cardholder_id','ASC')->get();

        Excel::create('drivers'.Carbon::now()->format('Ymdh'), function($excel) use ($drivers) {

            $excel->sheet('Sheet1', function($sheet) use ($drivers) {

                $arr = array();

                foreach($drivers as $driver) {
                    foreach($driver->trucks as $truck) {
                        foreach($driver->haulers as $hauler) {

                            $data =  array(
                            $driver->name,
                            $truck->plate_number,
                            $driver->phone_number,
                            $driver->substitute,
                            $hauler->name
                            );
                            array_push($arr, $data);

                        }
                    }
                }
                //set the titles
                $sheet->fromArray($arr,null,'A1',false,false)
                        ->setBorder('A1:E'.$drivers->count(),'thin')
                        ->prependRow(array(
                        'DRIVER NAME', 'PLATE NUMBER', 'PHONE NUMBER', 'SUBSTITUTE', 'OPERATOR'));
                $sheet->cells('A1:E1', function($cells) {
                            $cells->setBackground('#f1c40f'); 
                });

            });

        })->download('xlsx');
            
    }
}
