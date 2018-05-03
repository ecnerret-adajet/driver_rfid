<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Driver;
use App\Serve;
use App\Driverqueue;
use App\Log;
use DB;
use Flashy;

class ServingController extends Controller
{

    public function currentlyServing($driverqueue)
    {
        $serving = Serve::with('driver','driver.truck','driver.hauler','user','driver.image')
                            ->orderBy('id','DESC')
                            ->where('driverqueue_id',$driverqueue)
                            ->where('on_serving',1)
                            ->take(1)
                            ->get();
        
        return $serving;
    }

    public function servedToday($driverqueue)
    {

        //Get all drivers from location
        $located_serves = Serve::where('driverqueue_id',$driverqueue)
                            ->whereDate('created_at',Carbon::today())
                            ->pluck('cardholder_id');

        // get the cardholder with time out
        $log = Log::whereIn('CardholderID',$located_serves)
                ->whereDate('LocalTime',Carbon::today())
                ->where('Direction',2) 
                ->pluck('CardholderID');

        // Get only unique cardholder
        $get_unique_log = $log->unique('CardholderID');
        
        $served = Serve::with('driver','driver.truck','driver.hauler','driver.image')
                        ->whereDate('created_at',Carbon::today())
                        ->where('driverqueue_id',$driverqueue)
                        ->whereNotIn('cardholder',$get_unique_log)
                        ->orderBy('id','DESC')
                        ->get();
                                
        //   $arr = array();

        //    foreach($served as $x) {
        //         $data = array( 
        //             'served_id' => $x->id,
        //             'driver_id' => $x->driver->id,
        //             'avatar' => !empty($x->driver->image) ? $x->driver->image->avatar : $x->driver->avatar,
        //             'on_servering' => $x->on_serving,
        //             'served_start' => $x->served_start_date,
        //             'served_end_date' => $x->served_end_date,
        //             'driver_name' => empty($x->driver->name) ? null : $x->driver->name,
        //             'plate_number' => empty($x->driver->trucks) ? null : $x->driver->trucks->first()->plate_number,
        //             'hauler_name' => empty($x->driver->haulers) ? null : $x->driver->haulers->first()->name,
        //             'user_name' => empty($x->user->name) ? null : $x->user->name,
        //         );

        //         array_push($arr, $data);
        //    }
        
        return $served;

    }

    public function storeCurrentlyServing(Request $request, $driver_id, $log_id)
    {

        //Find object from log ID
        $log = Log::where('LogID',$log_id)->first();

        // Find the location queue
        $queue_id = Driverqueue::where('door',$log->DoorID)
                                ->where('controller',$log->ControllerID)
                                ->get()
                                ->first();

        $serving = new Serve;
        $serving->user_id = Auth::user()->id;
        $serving->driverqueue_id = $queue_id->id;
        $serving->log_id = $log->LogID;
        $serving->cardholder_id = $log->CardholderID;
        $serving->driver_id = $driver_id; // driver_id
        $serving->on_serving = 1;
        $serving->served_start_date = Carbon::now();
        $serving->save();

        flashy()->success('Driver successfully on serve!');
        return redirect('monitor/feed');

    }

}
