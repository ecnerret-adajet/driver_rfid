<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ConfirmDriver;
use App\Notifications\ConfirmReassign;
use App\Notifications\ConfirmLostCard;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Storage;
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
use App\Image;
use JavaScript;
use Ixudra\Curl\Facades\Curl;
use App\Truckversion;
use App\Version;

class DriversController extends Controller
{

    /**
     *  Driver revision method
     */
    public function driverRevision($id, $end_validity)
    {
        // search ID from Driver model
        $driver = Driver::findOrFail($id);

        //search frorm driver history truck when no truck found from reassignment 
        $last_driver_truck =  Version::select('plate_number','hauler')
                                        ->where('driver_id',$id)
                                        ->orderBy('id','desc')
                                        ->first();
 
        $version =  new Driverversion;
        $version->driver_id = $driver->id;
        $version->card_no = $driver->card_id;
        $version->cardholder_id = $driver->cardholder_id;
        $version->user_id = Auth::user()->id;
        $version->plate_number = empty($driver->truck->plate_number) ? empty($last_driver_truck->plate_number) ? 'N/A' : $last_driver_truck->plate_number : $driver->truck->plate_number;
        $version->vendor = empty($driver->hauler->name) ? $last_driver_truck->hauler : $driver->hauler->name;
        $version->start_date = $end_validity;
        $version->end_date = Carbon::now();
        $version->save();

        return $version;
    }

    /**
     *  Trucks revision method
     */
    public function truckRevision($id)
    {
        $driver = Driver::findOrFail($id);

        $version = new Truckversion;
        $version->user_id = Auth::user()->id;
        $version->cardholder_id = $driver->cardholder_id;
        $version->card_id = $driver->card_id;
        $version->driver_name = $driver->name;
        $version->plate_number = empty($driver->truck->plate_number) ? 'N/A' :  $driver->truck->plate_number;
        $version->hauler = empty($driver->hauler->name) ? 'N/A' : $driver->hauler->name;
        $version->save();

        return $version;
     }

    /**
     *  Testing SMS to notify approvers
     */
    public function sendSms($driver) 
    {
        $to = Truck::whereHas('drivers', function($q) use ($driver) {
            $q->where('id',$driver->id);
        })->first();

        $from = Driverversion::where('driver_id',$driver->id)->orderBy('id','DESC')->first();

        $message = urlencode(mb_convert_encoding('Driver Reassign: '.$driver->id.' Driver Name: '.$driver->name.' from '.$from.' to '.$to.'. [Ref Id] APPROVE or REJECT to submit.','utf-8','gb2312'));
        $response = Curl::to('http://10.96.2.20/sendsms?username=truckingsms&password=P@ssw0rd123&phonenumber=09175699879&message='.$message)->get();
        
        return $response;
    }

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
     *  Search Cardholder Deploy Name
     */
    public function getCardholderName($cardholder)
    {
       $cardholder = Cardholder::select('CardholderID','Name')
                                 ->where('CardholderID',$cardholder)
                                 ->first();
       return $cardholder->Name;
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
    public function create()
    {
        $clasifications = Clasification::pluck('name','id');
        $haulers = ['' => ''] +  Hauler::orderBy('id','DESC')->pluck('name','id')->all();
        // show only plate numbers without assigned driver

        $trucks = ['' => ''] + Truck::doesntHave('drivers')->where('availability',1)->orderBy('id','DESC')->pluck('plate_number','id')->all();


        // test query
        $driver_with_cardholder = Cardholder::select('Name')
                                ->with('drivers')
                                ->orderBy('CardholderID','desc')
                                ->take(5)
                                ->get();


        $cards = Card::orderBy('CardholderID','DESC')
                    ->whereNotIn('CardholderID', $this->removedCardholder())
                    ->where('AccessGroupID', 1) // card type
                    ->where('CardholderID','>=', 15)
                    ->where('CardholderID','!=', 0)
                    ->get()
                    ->pluck('full_deploy','CardID');
                            
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
            // 'avatar' => 'required|image|mimes:jpeg,png,jpg',
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
            
            $image = Image::doesntHave('driver')->orderBy('id','DESC')->first()->id;
            $card_rfid = $request->input('card_list');
            $driver = Auth::user()->drivers()->create($request->all());
    
            if($request->hasFile('avatar')){
                $driver->avatar = $request->file('avatar')->store('drivers','public');
            }
            
            $driver->name = strtoupper($request->input('name'));
            $driver->print_status = 1;
            $driver->availability = 0;
            $driver->notif_status = 1;
            $driver->image_id = $image;
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
        // $logs = Log::with('customers')
        //             ->whereNotIn('ControllerID',[1])
        //             ->where('CardholderID','=',$driver->cardholder->CardholderID)
        //             ->orderBy('LocalTime','DESC')
        //             ->get();

        $get_version_cardholder = Driverversion::where('driver_id',$driver->id)->pluck('cardholder_id');
        $get_driver_cardholder = $driver->cardholder->CardholderID;

        $all_cardholder = array_collapse([$get_version_cardholder, $driver->cardholder->CardholderID]);
        
                    
        // $logs = Log::with('customers')
        //        ->whereIn('CardholderId', $all_cardholder)
        //        ->orderBy('LocalTime','DESC')
        //        ->get();

        $logs = Log::where('CardholderID',$driver->cardholder->CardholderID)
                ->orderBy('LocalTime','DESC')
                ->get();

        // add logs from history logs
        //// code here ////

        $versions = Driverversion::where('driver_id',$driver->id)->orderBy('created_at','DESC')->get();

        return view('drivers.show', compact('driver','logs','versions'));
    }

    /**
     * 
     *  Search From Driver History When no plant number from logs history
     * 
     */
    public function findFromHistory($CardholderID)
    {
         $versions = Driverversion::select('plate_number','vendor','driver_id')
                    ->where('cardholder_id',$CardholderID)
                    ->orderBy('created_at','DESC')->get();

         return $versions;
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
       
        $cards = Card::select(DB::raw("CONCAT(CardNo,' - RFID Number ', CardholderID) AS deploy_number"),'CardID')
            ->orderBy('CardholderID','DESC')
            ->whereIn('CardholderID',[$driver->cardholder_id])
            ->where('AccessGroupID', 1) // card type 
            ->where('CardholderID','>=', 15)
            ->get()
            ->pluck('deploy_number','CardID');

        
        // when a driver has no cardholder assigned
        $driver_card = Driver::select('cardholder_id')->where('availability',1)->get();
        
        $card_driver = Card::select(DB::raw("CONCAT(CardNo,' - RFID Number ', CardholderID) AS deploy_number"),'CardID')
                    ->orderBy('CardholderID','DESC')
                    ->whereNotIn('CardholderID', $driver_card)
                    ->where('AccessGroupID', 1) // card type
                    ->where('CardholderID','>=', 15)
                    ->where('CardholderID','!=', 0)
                    ->get()
                    ->pluck('deploy_number','CardID');

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

        
        $trucks = Truck::whereHas('haulers',function($q) use ($driver){
            $q->where('name', $driver->hauler->name);
        })->orderBy('id','DESC')->pluck('plate_number','id');


        // $trucks = Truck::pluck('plate_number','id');

        return view('drivers.reassign',compact('driver','trucks','truck_subvendors'));
    }

    public function submitReassign(Request $request, Driver $driver)
    {

        $this->validate($request,[
            'truck_list' => 'required',
            'start_validity_date' => 'required|before:end_validity_date',
            'end_validity_date' => 'required'
        ]);

        // Driver's Revision model
        $this->driverRevision($driver->id, $request->input('end_validity_date'));
        // Truck's Revision model
        $this->truckRevision($driver->id);
        
        // Change driver's status upon submitting reassign
        $driver->update($request->all());
        $driver->availability = 0;
        $driver->notif_status = 1;
        $driver->save();

        // Deactivating RFID card from ASManager itself
        if(!empty($driver->card_id)) {
            $card = Card::where('CardID',$driver->card_id)->first();
            $card->CardStatus = 1; 
        }

        // Sync Truck Plate Number
        $driver->trucks()->sync((array) $request->input('truck_list'));
        
        // Sync Hauler Name 
        $drivers_truck = DB::table('hauler_truck')->select('hauler_id')
        ->where('truck_id',$request->input('truck_list'))->first();
        $driver->haulers()->sync((array) $drivers_truck); 
        
        // Record Activity to system logs
        $activity = activity()
        ->performedOn($driver)
        ->withProperties(['plate_number' => $driver->truck->plate_number])
        ->log('Reassigned');
            
        //Send email to supervisor for approval
        $setting = Setting::first();
        Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmReassign($driver));

        // Redirect flash animation after the form is process
        flashy()->success('Driver has successfully Reassigned!');
        return redirect('drivers');
    }


    /**
     * 
     *   Transfer driver to different Hauler
     * 
     */
    public function transferHauler(Driver $driver)
    {
        $haulers = Hauler::pluck('name','id');
        $trucks = Truck::pluck('plate_number','id');
        return view('drivers.transfer', compact('driver','haulers','trucks'));
    }

    public function transferHaulerSubmit(Request $request, Driver $driver)
    {
        $this->validate($request,[
            'hauler_list' => 'required',
            'truck_list' => 'required'
        ]);

        // Driver's Revision model
        $this->driverRevision($driver->id, $driver->end_validity_date);


        $driver->availability = 0;
        $driver->notif_status = 1;
        $driver->save();

        // Deactivating RFID card from ASManager itself
        if(!empty($driver->card_id)) {
            $card = Card::where('CardID',$driver->card_id)->first();
            $card->CardStatus = 1; 
        }

        $driver->haulers()->sync((array) $request->input('hauler_list'));
        $driver->trucks()->sync((array) $request->input('truck_list'));
 

        // Record Activity to system logs
        $activity = activity()
        ->performedOn($driver)
        ->withProperties(['hauler_name' => $driver->hauler->name])
        ->log('Transfer Hauler');
         
         //Send email to supervisor for approval
         $setting = Setting::first();
         Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmReassign($driver));

        
         // Redirect flash animation after the form is process
         flashy()->success('Driver has successfully Reassigned!');
         return redirect('drivers');
        
    }


    /**
     * 
     *  Disapproved Driver for edit function
     * 
     */
    public function disapprovedDriver(Driver $driver)
    {
        foreach($driver->trucks as $truck){
            foreach($truck->haulers as $hauler){
                $x = $hauler->id;
            }
        }
    
        $haulers = Hauler::orderBy('id','DESC')->pluck('name','id');

        if(count($driver->trucks) == 0) {
            $trucks = Truck::whereHas('haulers',function($q) use ($x){
                $q->where('id',$x);
            })->orderBy('id','DESC')->pluck('plate_number','id');
        } else {
            $trucks =  Truck::orderBy('id','DESC')->pluck('plate_number','id');
        }
       
         $cards = Card::orderBy('CardholderID','DESC')
                    ->whereNotIn('CardholderID', $this->removedCardholder())
                    ->where('AccessGroupID', 1) // card type
                    ->where('CardholderID','>=', 15)
                    ->where('CardholderID','!=', 0)
                    ->get()
                    ->pluck('full_deploy','CardID');

        
        // when a driver has no cardholder assigned
        $driver_card = Driver::select('cardholder_id')->where('availability',1)->get();
        
        $card_driver = Card::select(DB::raw("CONCAT(CardNo,' - RFID Number ', CardholderID) AS deploy_number"),'CardID')
                    ->orderBy('CardholderID','DESC')
                    ->whereNotIn('CardholderID', $driver_card)
                    ->where('AccessGroupID', 1) // card type
                    ->where('CardholderID','>=', 15)
                    ->where('CardholderID','!=', 0)
                    ->get()
                    ->pluck('deploy_number','CardID');

        return view('drivers.disapproved',compact(
            'driver',
            'clasifications',
            'card_driver',
            'haulers',
            'cards',
            'trucks'));


    }

    public function disapprovedDriverUpdate(Request $request, Driver $driver)
    {
        $this->validate($request, [
                'name' => 'required',
                'truck_list' => 'required',
                'phone_number' => 'required',
                'card_list' => 'required',
                'nbi_number' => 'required|max:8|min:8',
                'driver_license' => 'required|max:13|min:13',
                'start_validity_date' => 'required|before:end_validity_date',
                'end_validity_date' => 'required'
        ],[
            'truck_list.required' => 'Plate Number is required'
        ]);

        $card_rfid = $request->input('card_list');

        $driver->update($request->all());

        // if($request->hasFile('avatar')){
        //     $driver->avatar = $request->file('avatar')->store('drivers','public');
        // }        

        // This block is commented for the reason that only super admin uses this edit method
        // if(empty($driver->update_count)) {
        //     $driver->update_count = 1;
        // } else {
        //     $driver->update_count += 1;
        // }
        
        $driver->card()->associate($card_rfid);

        $driver->name = strtoupper($request->input('name'));

        $driver->save();

        $driver->trucks()->sync( (array) $request->input('truck_list'));

        $drivers_truck = DB::table('hauler_truck')->select('hauler_id')
        ->where('truck_id',$request->input('truck_list'))->first();

        $driver->haulers()->sync( (array) $drivers_truck);
        
        //send email back to approval
        $setting = Setting::first();
        Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmDriver($driver));
 
        flashy()->success('Driver has successfully updated!');
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

        $card_rfid = $request->input('card_list');

        $driver->update($request->all());
        $driver->availability = $request->input('availability');

        // if($request->hasFile('avatar')){
        //     $driver->avatar = $request->file('avatar')->store('drivers','public');
        // }        

        // This block is commented for the reason that only super admin uses this edit method
        // if(empty($driver->update_count)) {
        //     $driver->update_count = 1;
        // } else {
        //     $driver->update_count += 1;
        // }
        
        $driver->card()->associate($card_rfid);
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
        $driver->notif_status = 0;
        $driver->save();

        $card = Card::where('CardID',$driver->card_id)->first();
        $card->CardStatus = 1;
        $card->save();

        // Record Log Activity
        $activity = activity()
        ->performedOn($driver)
        ->withProperties(['card_no' => $driver->card->CardNo])
        ->log('Deactivate Card');

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
        $driver->notif_status = 0;
        $driver->availability = 1;
        $driver->save();

        $card = Card::where('CardID',$driver->card_id)->first();
        $card->CardStatus = 0;
        $card->save();

         // Record Log Activity
         $activity = activity()
         ->performedOn($driver)
         ->withProperties(['card_no' => $driver->card->CardNo])
         ->log('Activate Card');

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
        
        $driver_card = Driver::select('cardholder_id')->where('availability',1)->get();
        
        $cards = Card::select(DB::raw("CONCAT(CardNo,' - RFID Number ', CardholderID) AS deploy_number"),'CardID')
                    ->orderBy('CardholderID','DESC')
                    ->whereNotIn('CardholderID', $driver_card)
                    ->where('AccessGroupID', 1) // card type
                    ->where('CardholderID','>=', 15)
                    ->where('CardholderID','!=', 0)
                    ->get()
                    ->pluck('deploy_number','CardID');

        return view('drivers.lost',compact('driver','cards'));
    }

    public function lostCardStore(Request $request, $id)
    {
        $card_rfid = $request->input('card_list');
        $driver = Driver::findOrFail($id);

        // Driver's Revision model
        $this->driverRevision($driver->id, $driver->end_validity_date);
                
        // Driver's status upon submitting lost card
        $driver->print_status = 1;
        $driver->availability = 0;
        $driver->notif_status = 1;
        $driver->cardholder()->associate($driver->card->CardholderID);
        $driver->card()->associate($card_rfid);
        $driver->save();

        // Deactivating RFID card from ASManager itself
        if(!empty($driver->card_id)) {
            $card = Card::where('CardID',$driver->card_id)->first();
            $card->CardStatus = 1; 
        }

        // Record Activity to system logs
        $activity = activity()
        ->performedOn($driver)
        ->withProperties(['card_no' => $card_rfid])
        ->log('Reprint Card');

        //Send email to supervisor for approval
        $setting = Setting::first();
        Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmLostCard($driver));
        
        // Redirection animation upon submitting the form
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
    }
}
