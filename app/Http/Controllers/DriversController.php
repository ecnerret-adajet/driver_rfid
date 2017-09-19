<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ConfirmDriver;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;
use App\Clasification;
use App\Cardholder;
use App\Hauler;
use App\Driver;
use App\Truck;
use App\User;
use App\Binder;
use App\Card;
use Flashy;
use App\Setting;
use App\Driverversion;
use Excel;
use App\Log;
use DB;

class DriversController extends Controller
{

    public function vendorSubvendor()
    {
        $url = "http://10.96.4.39/trucking/rfc_get_vendor.php?server=lfug";
        $result = file_get_contents($url);
        $data = json_decode($result,true);

        return $data;
    }

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
        $haulers = ['' => ''] +  Hauler::orderBy('id','DESC')->pluck('name','id')->all();
        // show only plate numbers without assigned driver
        $trucks = ['' => ''] + Truck::doesntHave('drivers')->orderBy('id','DESC')->pluck('plate_number','id')->all();
        

        $cards = Card::orderBy('CardNo','DESC')->where('CardholderID','>=', 15)->pluck('CardNo','CardID');
        // $cards =  Card::doesntHave('cardholder.drivers')->orderBy('CardNo','DESC')->pluck('CardNo','CardID');
        // $cards =  Card::doesntHave('cardholder')->orderBy('CardNo','DESC')->pluck('CardNo','CardID');

        // return $cards->count();
        return view('drivers.create',compact('clasifications','trucks','cards','haulers'));
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
            'phone_number' => 'required|max:13|min:13',
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
        $driver->availability = 0;
        $driver->card()->associate($card_rfid);
        $driver->cardholder()->associate($driver->card->CardholderID);
        $driver->save();

        $driver->trucks()->attach($request->input('truck_list'));

        $drivers_truck = DB::table('hauler_truck')->select('hauler_id')
                            ->where('truck_id',$request->input('truck_list'))->first();

        $driver->haulers()->attach($drivers_truck); 
        
        //send email to supervisor for approval
        $setting = Setting::first();
        Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmDriver($driver));


        flashy()->success('Driver has successfully created!');
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

        $versions = Driverversion::where('driver_id',$driver->id)->orderBy('created_at','DESC')->get();
      
        return view('drivers.show', compact('driver','logs','versions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Driver $driver)
    {
        // $users = User::whereHas('roles', function($q){
        //     $q->where('id',3); // to revierwer
        // })->pluck('name','id');

        foreach($driver->trucks as $truck){
            foreach($truck->haulers as $hauler){
                $x = $hauler->id;
            }
        }
    
        $clasifications = Clasification::pluck('name','id');

        $haulers = Hauler::orderBy('id','DESC')->pluck('name','id');

        $trucks = Truck::whereHas('haulers',function($q) use ($x){
            $q->where('id',$x);
        })->orderBy('id','DESC')->pluck('plate_number','id');

        // $cards = ['' => ''] + Card::orderBy('CardNo','DESC')->pluck('CardNo','CardID')->all();
        $cards =  Card::orderBy('CardNo','DESC')->pluck('CardNo','CardID');
        
        return view('drivers.edit',compact(
            'driver',
            'clasifications',
            'haulers',
            'cards',
            'trucks'));

    }

    /**
    *
    * Reassignment Driver to another Plate Number
    *
    */
    public function reassign(Driver $driver)
    {
        foreach($driver->trucks as $truck) {
           $y = $truck->subvendor_description;
        }

        // $x = Hauler::select('id')->where('name', $y)->first();

        $trucks = Truck::whereHas('haulers',function($q) use ($y){
            $q->where('id', $y);
        })->orderBy('id','DESC')->pluck('plate_number','id');

        return view('drivers.reassign',compact('driver','trucks','truck_subvendors'));
    }

    public function submitReassign(Request $request, Driver $driver)
    {

        $this->validate($request,[
            'truck_list' => 'required'
        ]);

        foreach($driver->trucks as $truck) {
            $plate = $truck->plate_number;
            $start = $truck->start_validity_date;
            $end = $truck->end_validity_date;
        }

        foreach($driver->haulers as $hauler){
            $hauler = $hauler->name;
        }

        $version =  new Driverversion;
        $version->driver_id = $driver->id;
        $version->user_id = Auth::user()->id;
        $version->plate_number = $plate;
        $version->vendor = $hauler;
        $version->start_date = $request->input('end_validity_date');
        $version->end_date = Carbon::now();
        $version->save();

        
        $driver->update($request->all());
        $driver->availability = 0;
        $driver->save();
        $driver->trucks()->sync( (array) $request->input('truck_list'));
        

        $activity = activity()
        ->performedOn('App\Driver')        
        ->log('Reassigned');
        

        //send email to supervisor for approval
        $setting = Setting::first();
        Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmDriver($driver));

        
        flashy()->success('Driver has successfully Reassigned!');
        return redirect('drivers');
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

        flashy()->success('Driver has successfully updated!');
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

        // $activity = activity()
        // ->performedOn('App\Driver')
        // ->causedBy(Auth::user()->id)
        // ->log('Exported');
            
    }
}
