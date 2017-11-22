<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ConfirmReassign;
use App\Notifications\ConfirmDriver;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use App\Driver;
use App\Truck;
use App\Hauler;
use App\Card;
use App\Driverversion;
use App\Setting;
use Flashy;
use App\User;
use DB;

class HaulerOnlineController extends Controller
{

    public function index()
    {
        return view('haulerOnline.hauler-home');
    }

    public function haulerOnlineReassign(Driver $driver)
    {
        foreach($driver->trucks as $truck) {
            $y = $truck->subvendor_description;
         }
          
         $trucks = Truck::whereHas('haulers',function($q) use ($driver){
             $q->where('name', $driver->hauler->name);
         })->orderBy('id','DESC')->pluck('plate_number','id');
 
         return view('haulerOnline.hauler-reassign',compact('driver','trucks','truck_subvendors'));
    }


    /**
     *  Driver revision method
     */
    public function driverRevision($id, $end_validity)
    {
        $driver = Driver::findOrFail($id);

        $version =  new Driverversion;
        $version->driver_id = $driver->id;
        $version->card_no = $driver->card_id;
        $version->cardholder_id = $driver->cardholder_id;
        $version->user_id = Auth::user()->id;
        $version->plate_number = $driver->truck->plate_number;
        $version->vendor = $driver->hauler->name;
        $version->start_date = $end_validity;
        $version->end_date = Carbon::now();
        $version->save();

        return $version;
    }

    public function haulerOnlineReassignSubmit(Request $request, Driver $driver)
    {

        $this->validate($request,[
            'truck_list' => 'required',
            'start_validity_date' => 'required|before:end_validity_date',
            'end_validity_date' => 'required'
        ]);

        // Driver's Revision model
        $this->driverRevision($driver->id, $request->input('end_validity_date'));
        
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
        ->log('Reassigned');
        
        //Send email to supervisor for approval
        $setting = Setting::first();
        Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmReassign($driver));

        // Redirect flash animation after the form is process
        flashy()->success('Driver has successfully Reassigned!');
        return redirect('users/hauler/online');
    }

    /**
     *  Request a driver creation from haluer online form
     * 
     */
    public function haulerDriverCreate()
    {        
        $user = User::findOrFail(Auth::user()->id);

        // Show only truck with the same hauler as the user assigned
        $trucks = Truck::whereHas('haulers',function($q) use ($user){
            $q->where('name', $user->hauler->name);
        })->orderBy('id','DESC')->pluck('plate_number','id');
                       
        return view('haulerOnline.create',compact('trucks'));
    }

    public function haulerDriverStore(Request $request)
    {
        $this->validate($request, [
            'avatar' => 'required|image|mimes:jpeg,png,jpg',
            'name' => 'required|regex:/^[\pL\s\-]+$/u|max:255|unique:drivers',
            'truck_list' => 'required',
            'phone_number' => 'required|max:13|min:13',
            'nbi_number' => 'required|max:8|min:8',
            'driver_license' => 'required|max:13|min:13',
            'contact_person' => 'required',                
            'contact_phone' => 'required|max:13|min:13',                
            'address' => 'required',                
        ],[
            'truck_list.required' => 'Plate Number is required'
        ]);

        // Find Auth user with assigned hauler
        $user = User::findOrFail(Auth::user()->id);

        // Store Driver Information
        $driver = Auth::user()->drivers()->create($request->all());
        if($request->hasFile('avatar')){
            $driver->avatar = $request->file('avatar')->store('drivers');
        }
        $driver->name = strtoupper($request->input('name'));
        $driver->print_status = 1;
        $driver->availability = 0;
        $driver->notif_status = 1;
        $driver->save();

        // Set the relationships to trucks and hauler
        $driver->trucks()->attach($request->input('truck_list'));
        $driver->haulers()->attach($user->hauler->id);

        //send email to supervisor for approval
        $setting = Setting::first();
        Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmDriver($driver));

        flashy()->success('Driver has successfully created!');
        return redirect('users/hauler/online');
    }

    public function haulerEditUser(User $user)
    {   

        if(Gate::denies('view', $user)) {
            flashy()->error('Nope, You Cannot Do That!');
            return redirect('users/hauler/online');
        }

        return view('haulerOnline.hauler-edit-user',compact('user'));
    }

    public function haulerUpdateUser(Request $request, User $user)
    {

        if(Gate::denies('view', $user)) {
            return redirect('users/hauler/online');
        }

        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));    
        }

        $user->update($input);
        $user->save();

        flashy()->success('Driver has successfully updated!');
        return redirect('users/hauler/online');
    }


}
