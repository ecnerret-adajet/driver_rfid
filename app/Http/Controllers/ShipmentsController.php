<?php

namespace App\Http\Controllers;

use App\Log;
use App\Shipment;
use Carbon\Carbon;
use App\QueueEntry;
use App\Driverqueue;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Session;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;
use App\Transformers\WithShipmentsTransformer;

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

        $served = Shipment::with('driver','driver.truck','driver.hauler','driver.image','queueEntry:LogID,isTappedGateFirst,isDRCompleted')
                        ->whereDate('created_at',Carbon::today())
                        ->where('ControllerID', $driverqueue->controller)
                        ->where('DoorID',$driverqueue->door)
                        ->whereNotIn('CardholderID',$get_unique_log)
                        ->orderBy('id','DESC')
                        ->get();

        $manager = new Manager();
        $resource = new Collection($served, new WithShipmentsTransformer());

        return $manager->createData($resource)->toArray();

        // return $served;

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

        Session::put('queueDate', Carbon::now()->subDay());
        $dateSearch = Session::get('queueDate');

        // get all queue entries within the day in all location
        $driverqueues = Driverqueue::pluck('id');

        // $checkTruckscaleOut = collect(Log::truckscaleOutQueueArray())->unique();

        $queues = QueueEntry::whereIn('driverqueue_id',$driverqueues)
                            // ->whereNotIn('CardholderID',$checkTruckscaleOut->values()->all())
                            ->where('LocalTime', '>=', $dateSearch)
                            // ->whereNull('shipment_number')
                            ->doesntHave('shipment')
                            ->whereNotNull('driver_availability')
                            ->whereNotNull('truck_availability')
                            ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                            ->whereNotNull('isTappedGateFirst')
                            ->orderBy('LocalTime','ASC')
                            ->get()
                            ->unique('CardholderID')
                            ->values()
                            ->all();

        $queueObject = array();

        foreach($queues as $key => $log)  {
                $amp = '&';
                $data = array(
                    'LogID' => $log->LogID.$amp,
                );
                array_push($queueObject, $data);
        }

        $collection = collect($queueObject);
        $LogID =  'LogID='.$collection->implode('LogID', 'LogID=');
        $response = Curl::to('http://10.96.4.39/sapservice/api/assignedshipment2')
        ->withContentType('application/x-www-form-urlencoded')
        ->withData( $LogID )
        ->post();

        return $response;
    }

     // Test Shipment for drivers who checkout the plant
    public function checkShipmentStart() {

        // get all queue entries within the day in all location
        $driverqueues = Driverqueue::all();

        foreach ($driverqueues as $driverqueue) {
            $check_truckscale_out = Log::truckscaleOutLocationObject($driverqueue->ts_out_controller, null);
            $log_lineups = $check_truckscale_out->unique('CardholderID');
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

        }

        $collection = collect($queueObject);
        $LogID =  'LogID='.$collection->implode('LogID', 'LogID=');
        $response = Curl::to('http://10.96.4.39/sapservice/api/assignedshipment')
        ->withContentType('application/x-www-form-urlencoded')
        ->withData( $LogID )
        ->post();

        return $response;
    }


}
