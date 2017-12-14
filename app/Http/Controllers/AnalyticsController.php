<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;
use App\Driver;
use App\Truck;
use App\Log;
use App\Cardholder;
use App\Pickup;
use App\Hauler;
use DB;

class AnalyticsController extends Controller
{

    public function index()
    {
        $stats = $this->driversVsTrucks();
       
        // Top Hauler;
        $labels = array();
        $values = array();
        foreach($this->topHaulers() as $top) {
            $labels[] = $top->name;
            $values[] = $top->drivers->count();
        }

        return view('visuals.analytics',compact('stats','labels','values'));
    }

    public function driversVsTrucks()
    {
        $driver = Driver::thisMonth()->count();
        $truck = Truck::thisMonth()->count();

        $data = array(
            'driver' => $driver, 
            'truck' => $truck, 
        );
        
        return $data;

    }

    public function dailyEntries()
    {
        // Daily Truck Entries 
        $pickup_cards = Cardholder::select('CardholderID')
        ->where('Name', 'LIKE', '%Pickup%')
        ->get();

        $logs = Log::whereNotIn('ControllerID',[1])
        ->whereNotIn('CardholderID',$pickup_cards)
        ->where('CardholderID', '>=', 1)
        ->where('Direction', 1)
        ->whereDate('LocalTime', '>=', Carbon::now())
        ->orderBy('LocalTime','DESC')->count();

        // Daily Pickup Entries
        $pickups = Pickup::whereDate('created_at', '>=', Carbon::now())->count();

        //Daily Reassign Entries
        $reassigns =  Activity::where('description', 'Reassigned')
                            ->whereDate('created_at', '>=', Carbon::now())->count();
        
        $data = array (
            'trucks' => $logs,
            'pickups' => $pickups,
            'reassigns' => $reassigns
        );
        return $data;
    }

    public function weeklyEntries()
    {
        // Weekly Truck Entries 
        $pickup_cards = Cardholder::select('CardholderID')
        ->where('Name', 'LIKE', '%Pickup%')
        ->get();

        $logs = Log::whereNotIn('ControllerID',[1])
        ->whereNotIn('CardholderID',$pickup_cards)
        ->where('CardholderID', '>=', 1)
        ->where('Direction', 1)
        ->whereBetween('LocalTime', [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()])
        ->orderBy('LocalTime','DESC')->count();

        // Weekly Pickup Entries
        $pickups = Pickup::whereBetween('created_at', [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()])->count();

        //Weekly Reassign Entries
        $reassigns =  Activity::where('description', 'Reassigned')
                            ->whereBetween('created_at', [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()])->count();
        
        $data = array (
            'trucks' => $logs,
            'pickups' => $pickups,
            'reassigns' => $reassigns
        );
        return $data;
    }

    public function monthlyEntries()
    {
        // Monthly Truck Entries 
        $pickup_cards = Cardholder::select('CardholderID')
        ->where('Name', 'LIKE', '%Pickup%')
        ->get();

        $logs = Log::whereNotIn('ControllerID',[1])
        ->whereNotIn('CardholderID',$pickup_cards)
        ->where('CardholderID', '>=', 1)
        ->where('Direction', 1)
        ->whereMonth('LocalTime', Carbon::now()->month)
        ->orderBy('LocalTime','DESC')->count();

        // Monthly Pickup Entries
        $pickups = Pickup::whereMonth('created_at', Carbon::now()->month)->count();

        //Monthly Reassign Entries
        $reassigns =  Activity::where('description', 'Reassigned')
                            ->whereMonth('created_at', Carbon::now()->month)->count();
        
        $data = array (
            'trucks' => $logs,
            'pickups' => $pickups,
            'reassigns' => $reassigns
        );
        return $data;
    }

    public function topHaulers()
    {
        $top_hauler = Hauler::withCount('drivers')
        ->orderBy('drivers_count','desc')
        ->take(10)
        ->get(); // should be pluck

        // $label = array();
        // $value = array();
        // foreach($top_hauler as $top) {
        //     $label[] = [$top->name => $top->drivers->count()];
        //     $value[] = [$top->name => $top->drivers->count()];
        // }

        return $top_hauler;
    }


    /**
     *  Driver top entries
     */

    public function detailEntries()
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

        // Remove all cardholder without driver assigned
        $not_driver = array_collapse([$pickup_cards, $guard_cards, $executive_cards]);

        // All Plant in 
        $plant_in = Log::select('CardholderID','Direction','LocalTime')
        ->whereIn('DoorID',[3]) // barrier
        ->whereNotIn('CardholderID',$not_driver)
        ->where('CardholderID', '>=', 15)
        ->where('Direction', 1)
        ->orderBy('LocalTime','DESC')
        ->whereBetween('LocalTime', [Carbon::now()->subDays(1), Carbon::now()])
        // ->with('driver')
        ->get();

        // All Plant out
        $plant_out = Log::select('CardholderID','Direction','LocalTime')
        ->whereIn('DoorID',[3]) // barrier
        ->whereNotIn('CardholderID',$not_driver)
        ->where('CardholderID', '>=', 15)
        ->where('Direction', 2)
        ->orderBy('LocalTime','DESC')
        ->whereDate('LocalTime', '>=', Carbon::now())
        // ->with('driver')
        ->get();

        $all_entries = Log::select('LogID','CardholderID','ControllerID')
        ->whereIn('DoorID',[3]) // barrier
        ->whereNotIn('CardholderID',$not_driver)
        ->where('CardholderID', '>=', 15)
        ->whereDate('LocalTime', '>=', Carbon::now())
        ->orderBy('LocalTime','DESC')
        ->with('driver')
        ->get();

        $filtered_entries = $all_entries->unique('CardholderID');

        $arr = array();

        foreach($filtered_entries as $entry){
            foreach($entry->drivers as $driver) {
                        
                        $data = array(
                        'LogID' =>  $entry->LogID,
                        'CardholderID' =>  empty($entry->CardholderID) ? '' : $entry->CardholderID,
                        'driver' => empty($driver->name) ? '' : $driver->name,
                        'plate_number' => empty($driver->truck->plate_number) ? '' : $driver->truck->plate_number,
                        'inLocalTime' =>  empty($this->plantIn($entry->CardholderID)) ? '' : $this->plantIn($entry->CardholderID),
                        'outLocalTime' =>  empty($this->plantOut($entry->CardholderID)) ? '' : $this->plantOut($entry->CardholderID),
                        );

                        array_push($arr, $data);
                    }
        }        
        return $arr;
    }

    public function plantIn($cardholder) 
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

        // Remove all cardholder without driver assigned
        $not_driver = array_collapse([$pickup_cards, $guard_cards, $executive_cards]);

        // All Plant in 
        $plant_in = Log::select('CardholderID','Direction','LocalTime')
                    ->where('CardholderID',$cardholder)
                    ->whereIn('DoorID',[3]) // barrier
                    ->whereNotIn('CardholderID',$not_driver)
                    ->where('CardholderID', '>=', 15)
                    ->where('Direction', 1)
                    ->orderBy('LocalTime','DESC')
                    ->whereBetween('LocalTime', [Carbon::now()->subDays(1), Carbon::now()])
                    ->first();

        if(empty($plant_in)) {
            $x = null;
        } else {
            $x = $plant_in->LocalTime;
        }

        return $x;
    }

    public function plantOut($cardholder)
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

        // Remove all cardholder without driver assigned
        $not_driver = array_collapse([$pickup_cards, $guard_cards, $executive_cards]);

        // All Plant out
        $plant_out = Log::select('CardholderID','Direction','LocalTime')
                    ->whereIn('DoorID',[3]) // barrier
                    ->where('CardholderID',$cardholder)
                    ->whereNotIn('CardholderID',$not_driver)
                    ->where('CardholderID', '>=', 15)
                    ->where('Direction', 2)
                    ->orderBy('LocalTime','DESC')
                    ->whereDate('LocalTime', '>=', Carbon::now())
                    ->first();
        
        if(empty($plant_out)) {
            $x = null;
        } else {
            $x = $plant_out->LocalTime;
        }
        
        return $x;
    }

}
