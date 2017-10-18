<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ConfirmDriver;
use App\Notifications\ConfirmReassign;
use App\Notifications\ConfirmLostCard;
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
use Image;
use JavaScript;

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

        $trucks = ['' => ''] + Truck::doesntHave('drivers')->where('availability',1)->orderBy('id','DESC')->pluck('plate_number','id')->all();
        
        $driver_card = Driver::select('cardholder_id')->where('availability',1)->get();
        $truck_card = Truck::select('card_id')->whereNotNull('card_id')->get();
        $cardholder_from_truck = Card::select('CardID')->whereIn('CardID',$truck_card)->get();
        $cardholder_card = Card::select('CardID')->whereIn('CardholderID',$driver_card)->get();
        $merged = $cardholder_card->merge($cardholder_from_truck);
        
        $cards = Card::orderBy('CardholderID','DESC')
                ->whereNotIn('CardholderID', $driver_card)
                ->whereNotIn('CardID', $truck_card)
                ->where('CardholderID','>=', 15)
                ->where('CardholderID','!=', 0)->pluck('CardNo','CardID')->take(20);
                
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
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'name' => 'required|max:255|unique:drivers',
            'card_list' => 'required',
            'truck_list' => 'required',
            'phone_number' => 'required|max:13|min:13',
            'nbi_number' => 'required|max:8|min:8',
            'driver_license' => 'required|max:13|min:13',
            'start_validity_date' => 'required|before:end_validity_date',
            'end_validity_date' => 'required'
                
        ],[
            'truck_list.required' => 'Plate Number is required'
        ]);


            $card_rfid = $request->input('card_list');
            $driver = Auth::user()->drivers()->create($request->all());
    
            if($request->hasFile('avatar')){
                $driver->avatar = $request->file('avatar')->store('drivers');
            }
    
            // if($request->hasFile('avatar')){
            //     $avatar = $request->file('avatar');
            //     $filename = time() . '.' .$avatar->extension();
            //     Image::make($avatar)->resize(256,256)->save( storage_path('app/drivers/' . $filename ) );  
            //     $driver->avatar = 'drivers/'.$filename;
            // } 
            
            $driver->name = strtoupper($request->input('name'));
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

        if(count($driver->trucks) == 0) {
            
            $trucks = Truck::whereHas('haulers',function($q) use ($x){
                $q->where('id',$x);
            })->orderBy('id','DESC')->pluck('plate_number','id');

        } else {

            $trucks =  Truck::orderBy('id','DESC')->pluck('plate_number','id');
        }

        // $cards = ['' => ''] + Card::orderBy('CardNo','DESC')->pluck('CardNo','CardID')->all();
        // $cards =  Card::orderBy('CardNo','DESC')->pluck('CardNo','CardID');

        // if a driver has no assign card
        $driver_card = Driver::where('id',$driver->id)
        ->where('availability',1)->first();

        $cards = Card::orderBy('CardID','DESC')
        ->whereIn('CardholderID',[$driver_card->cardholder_id])
        ->where('CardID', '!=', $driver_card->card_id)
        ->where('CardholderID','>=', 15)
        ->where('CardholderID','!=', 0)->pluck('CardNo','CardID');

        // when a driver has a card assigned
        $card_driver = Card::orderBy('CardNo','DESC')->pluck('CardNo','CardID');
        
        return view('drivers.edit',compact(
            'driver',
            'clasifications',
            'card_driver',
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

        // $trucks = Truck::whereHas('haulers',function($q) use ($y){
        //     $q->where('id', $y);
        // })->orderBy('id','DESC')->pluck('plate_number','id');


        $trucks = Truck::pluck('plate_number','id');

        return view('drivers.reassign',compact('driver','trucks','truck_subvendors'));
    }

    public function submitReassign(Request $request, Driver $driver)
    {

        $this->validate($request,[
            'truck_list' => 'required',
            'start_validity_date' => 'required|before:end_validity_date',
            'end_validity_date' => 'required'
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
        $version->card_no = $driver->card_id;
        $version->user_id = Auth::user()->id;
        $version->plate_number = $plate;
        $version->vendor = $hauler;
        $version->start_date = $request->input('end_validity_date');
        $version->end_date = Carbon::now();
        $version->save();

        
        $driver->update($request->all());
        $driver->availability = 0;
        $driver->notif_status = 0;

        // Deactivating RFID card from ASManager itself
        if(!empty($driver->card_id)) {
            $card = Card::where('CardID',$driver->card_id)->first();
            $card->CardStatus = 1; 
        }

        $driver->save();
        $driver->trucks()->sync( (array) $request->input('truck_list'));
        

        $activity = activity()
        ->log('Reassigned');
        

        //send email to supervisor for approval
        $setting = Setting::first();
        Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmReassign($driver));

        
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
                // 'hauler_list' => 'required',
                'truck_list' => 'required',
                'phone_number' => 'required',
                // 'card_list' => 'required',
                // 'clasification_list' => 'required',
                // 'start_validity_date' => 'required|before:end_validity_date',
                // 'end_validity_date' => 'required'
        ],[
            'truck_list.required' => 'Plate Number is required'
        ]);

        // $card_rfid = $request->input('card_list');
        // $clasification_id = $request->input('clasification_list');

        $driver->update($request->all());
        if($request->hasFile('avatar')){
            $driver->avatar = $request->file('avatar')->store('drivers');
        }        

        // if(empty($driver->update_count)) {
        //     $driver->update_count = 1;
        // } else {
        //     $driver->update_count += 1;
        // }
        
        // $driver->card()->associate($card_rfid);
        // $driver->clasification()->associate($clasification_id);

        $driver->name = strtoupper($request->input('name'));

        $driver->save();

        $driver->trucks()->sync( (array) $request->input('truck_list'));

        $drivers_truck = DB::table('hauler_truck')->select('hauler_id')
        ->where('truck_id',$request->input('truck_list'))->first();

        $driver->haulers()->sync( (array) $drivers_truck); 
 
        flashy()->success('Driver has successfully updated!');
        return redirect('drivers');
    }

    /*
    *
    * Deactive driver rfid
    *
    */
    public function deactivateRfid(Request $request, $id)
    {
        $driver = Driver::where('id',$id)->first();
        $driver->availability = 0;
        $driver->save();

        $card = Card::where('CardID',$driver->card_id)->first();
        $card->CardStatus = 1;
        $card->save();

        flashy()->success('Driver has successfully deactivated!');
        return redirect('drivers');
    }

    /*
    *
    *
    * Enable driver RFID
    *
    */
    public function activateRfid(Request $request, $id)
    {
        $driver = Driver::where('id',$id)->first();
        $driver->availability = 1;
        $driver->save();

        $card = Card::where('CardID',$driver->card_id)->first();
        $card->CardStatus = 0;
        $card->save();

        flashy()->success('Driver has successfully activated!');
        return redirect('drivers');
    }

    /*
    *
    * Lost Card Function
    *
    */
    public function lostCardCreate($id)
    {
        $driver = Driver::findOrFail($id);
        $cards = Card::orderBy('CardNo','DESC')->pluck('CardNo','CardID');
        return view('drivers.lost',compact('driver','cards'));
    }

    public function lostCardStore(Request $request, $id)
    {
        $card_rfid = $request->input('card_list');

        $driver = Driver::findOrFail($id);

        foreach($driver->trucks as $truck) {
            $plate = $truck->plate_number;
        }
        foreach($driver->haulers as $hauler) {
            $hauler = $hauler->name;
        }

        $version =  new Driverversion;
        $version->driver_id = $driver->id;
        $version->card_no = $driver->card_id;
        $version->user_id = Auth::user()->id;
        $version->plate_number = $plate;
        $version->vendor = $hauler;
        $version->start_date = $driver->start_validity_date;
        $version->end_date = $driver->end_validity_date;
        $version->save();
        
        $driver->print_status = 1;
        $driver->availability = 0;
        $driver->notif_status = 0;
        $driver->card()->associate($card_rfid);
        $driver->cardholder()->associate($driver->card->CardholderID);
        
        // if(!empty($driver->card_id)) {
        //     $card = Card::where('CardID',$driver->card_id)->first();
        //     $card->CardStatus = 1; 
        // }
        
        $driver->save();

        $activity = activity()
        ->log('Lost RFID Card');

        //send email to supervisor for approval
        $setting = Setting::first();
        Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmLostCard($driver));
        
        flashy()->success('Driver has successfully requested for lost card!');
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
