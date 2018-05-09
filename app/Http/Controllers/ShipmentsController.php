<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Log;
use App\Shipment;
use App\Driverqueue;
use Ixudra\Curl\Facades\Curl;

class ShipmentsController extends Controller
{
    // Get all assigned shipment based from location queue

    public function servedToday(Driverqueue $driverqueue)
    {
        //Get all drivers from location
        $located_serves = Shipment::where('ControllerID', $driverqueue->controller)
                            ->where('DoorID',$driverqueue->door)
                            ->whereDate('created_at',Carbon::today())
                            ->pluck('CardholderID');

        // get the cardholder with time out
        $log = Log::whereIn('CardholderID',$located_serves)
                ->whereDate('LocalTime',Carbon::today())
                ->where('Direction',2) 
                ->pluck('CardholderID');

        // Get only unique cardholder
        $get_unique_log = $log->unique('CardholderID');
        
        $served = Shipment::with('driver','driver.truck','driver.hauler','driver.image')
                        ->whereDate('created_at',Carbon::today())
                        ->where('ControllerID', $driverqueue->controller)
                        ->where('DoorID',$driverqueue->door)
                        ->whereNotIn('CardholderID',$get_unique_log)
                        ->orderBy('id','DESC')
                        ->get();

        return $served;

    }

    // Get the last shipment assigned based from location queue

    public function currentlyServing(Driverqueue $driverqueue) 
    {
        $last_served = Shipment::with('driver','driver.truck','driver.hauler','driver.image')
                        ->orderBy('id','DESC')
                        ->where('DoorID',$driverqueue->door)
                        ->where('ControllerID',$driverqueue->controller)
                        ->take(1)
                        ->get();

        return $last_served;
    }

    // Test Shipment assigned 
    public function shipmentAssigned() {
        
          // get all queue entries within the day in all location
        $driverqueues = Driverqueue::all();
        
        foreach($driverqueues as $driverqueue) {
            
            $check_truckscale_out = Log::truckscaleOutLocationDate($driverqueue->ts_out_controller,Carbon::today()->subDays(1));
            $gateEntries =  Log::barrierLocationDate($driverqueue->gate->door,$driverqueue->gate->controller,Carbon::today());
            $result_lineups = Log::queueLocation($driverqueue->door, $driverqueue->controller, $check_truckscale_out, $gateEntries, Carbon::today());
            $log_lineups = $result_lineups->unique('CardholderID');
            $queueObject = array();

            foreach($log_lineups as $key => $log)  {
                foreach($log->drivers as $x => $driver) {
                    $amp = '&';
                    $data = array(
                        'LogID' => $log->LogID.$amp,
                    );
                    array_push($queueObject, $data);
                }
            }

            $collection = collect($queueObject);
            $LogID =  'LogID='.$collection->implode('LogID', 'LogID=');
            $response = Curl::to('http://10.96.4.39/sapservice/api/assignedshipment')
            ->withContentType('application/x-www-form-urlencoded')
            ->withData( $LogID )
            ->post();

        }

        return $check_truckscale_out;
    }
}
