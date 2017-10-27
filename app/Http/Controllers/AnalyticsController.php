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

}
