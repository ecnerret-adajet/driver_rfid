<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ixudra\Curl\Facades\Curl;
use App\Log;
use App\Driver;
use App\Truck;
use Carbon\Carbon;
use App\Pickup;
use App\Cardholder;
use App\Card;
use App\Serve;
use App\Shipment;
use App\Driverqueue;
use DB;

class QueuesController extends Controller
{

    public function autoShipmentEnd()
    {
        $driverqueue = Driverqueue::findOrFail(1);

        // MANILA QUEUE
          // Get the total truckscale Out from truck monitoring today
        $check_truckscale_out = Log::truckscaleOutLocation($driverqueue->ts_out_controller);
        // Get the queue result
        $result_lineups = Log::queueLocationShipment($driverqueue->door, $driverqueue->controller, $check_truckscale_out, Carbon::today());
        // Get the unique result from Cardholder
        $log_lineups = $result_lineups->unique('CardholderID');

        $queueObject = array();

        foreach($log_lineups as $key => $log)  {
            foreach($log->drivers as $driver) {

                $data = array(
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number.'&',
                );
                array_push($queueObject, $data);

            }
        }

        $collection = collect($queueObject);
        $plate_number =  'plate_number='.$collection->implode('plate_number', 'plate_number=');

        $response = Curl::to('http://10.96.4.39/sapservice/api/assignedshipment')
        ->withContentType('application/x-www-form-urlencoded')
        ->withData( $plate_number )
        ->post();

        // $log_id = 

        // foreach($response as $res) {
        //      $shipment = Shipment::firstOrCreate(
        //     ['LogID'=>$res->,'user_id'=>$user_id],
        //     );
        //     $shipment->save();
        // }
        
        // if($shipment->wasRecentlyCreated){
        //     return 'Created successfully';
        // } else {
        //     echo 'Already exist';
        // }

           
        return json_decode($response, true);
        
   }

    public function index()
    {
        return view('queues.index');
    }

    public function pickups()
    {   
        $pickups = Pickup::where('created_at', '>=', Carbon::today()->subDay(3))
                    ->orderBy('created_at','DESC')
                    ->with('cardholder','user')
                    ->get();

        return $pickups;

    }

    // Manila Queue Location Method
    public function deliveries()
    {
        // Get drivers with truckscale out within the day
        $check_truckscale_out = Log::truckscaleOut();
    
        // Get the total drivers who tapped from Gate RFID
        $manilaGate =  Log::barrierLocation(3,2);

        // MNL (Pfmc) queueing location
        $logs = Log::queueLocation(0,1,$check_truckscale_out,$manilaGate,Carbon::today());

        // check if truck has complete dr into pluck
        // $queue_plate_number = $logs->pluck('driver.truck.plate_number');
        // $card_queue = Truck::callLastTripCardholder($queue_plate_number);

        //Get only active driver and trucks from DR Submitted result
        // $activeEntries = Driver::getActiveDriverTruck($card_queue);

        // Get the unique result from queue
        $mnl_queue = $logs->unique('CardholderID');

        // compare if in is greater thatn time out within the day
        // then add to queueu

        $arr = array();
        
        foreach($mnl_queue as $key => $log) {
            foreach($log->drivers as $driver) {

                $data = array(
                    'log_id' => substr($log->LogID, -4),
                    'LogID' => $log->LogID,
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' =>empty($driver->truck->plate_number) ? 'NO PLATE' : Truck::callLastTrip($driver->truck->plate_number),
                    'on_serving' =>  Shipment::checkIfShipped($log->CardholderID,null)->first()
                );
                array_push($arr, $data);

            }
        }

        return  $arr;
    }

    // MNL (PFMC) assigned shipment
    public function assignedShipment() 
    {

        // Get drivers with truckscale out within the day
        $check_truckscale_out = Log::truckscaleOut();

          // Get the total drivers who tapped from Gate RFID
        $manilaGate =  Log::barrierLocation(3,2);

        // MNL (Pfmc) queueing location
        $logs = Log::queueLocation(0,1,$check_truckscale_out,$manilaGate,Carbon::today());

        // Get the unique result from queue
        $mnl_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($mnl_queue as $key => $log) {
            foreach($log->drivers as $driver) {
                if(count($driver->serves->where('created_at','>=',Carbon::today())) != 0) {

                // if(!empty($driver->truck->plate_number)) {
                //     $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                //     $z = str_replace('_','',$x);
                //     $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                // }

                $data = array(
                    'log_id' => substr($log->LogID, -4),
                    'LogID' => $log->LogID,
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' =>empty($driver->truck->plate_number) ? 'NO PLATE' : Truck::callLastTrip($driver->truck->plate_number), 
                    'on_serving' => 1,

                );

                array_push($arr, $data);

                }
            }
        }

        return $arr;
    }

    // MNL (PFMC) open shipment
    public function openShipment()
    {
            
        // Get drivers with truckscale out within the day
        $check_truckscale_out = Log::truckscaleOut();

          // Get the total drivers who tapped from Gate RFID
        $manilaGate =  Log::barrierLocation(3,2);

        // MNL (Pfmc) queueing location
        $logs = Log::queueLocation(0,1,$check_truckscale_out,$manilaGate,Carbon::today());

        // Get the unique result from queue
        $mnl_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($mnl_queue as $key => $log) {
            foreach($log->drivers as $driver) {
                if(count($driver->serves->where('created_at','>=',Carbon::today()))  == 0) {

                // if(!empty($driver->truck->plate_number)) {
                //     $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                //     $z = str_replace('_','',$x);
                //     $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                // }

                $data = array(
                    'log_id' => substr($log->LogID, -4), // $key + 1
                    'LogID' => $log->LogID,
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' =>empty($driver->truck->plate_number) ? 'NO PLATE' : Truck::callLastTrip($driver->truck->plate_number), 
                    // 'driver_status' => $driver->availability,
                    'on_serving' => empty($driver->serves->where('created_at','>=',Carbon::today())->first()->on_serving) ? null : $driver->serves->first()->on_serving,

                );

                array_push($arr, $data);

                }
            }
        }

        return $arr;
    }

    // MNL (PFMC) deliveries count
    public function getDeliveriesCount()
    {
        $totalAssiged = count($this->assignedShipment());
        $totalOpen = count($this->openShipment());

        // Get drivers with truckscale out within the day
        $check_truckscale_out = Log::truckscaleOut();

        // Check Trucks who has Truckscale in but not out        
        $check_truckscale_in = Log::trucksInPlant(1,4,$check_truckscale_out)->count();

        $data = array(
            'totalAssigned' => $totalAssiged,
            'totalOpen' => $totalOpen,
            'current_in_plant' => $check_truckscale_in
        );
        return $data;
    }





    /**
     * BATAAN Queueing functions
     */



    // Manila Queue Location Method
    public function btnDeliveries()
    {
        // Get drivers with truckscale out within the day
        $check_truckscale_out = Log::btnTruckscaleOut();

        // Get the total drivers who tapped from Gate RFID
        $bataanGate =  Log::barrierLocation(0,9);
    
        // BTN (MGC) queueing location
        $logs = Log::queueLocation(2,7,$check_truckscale_out,$bataanGate,Carbon::today());

        // Get the unique result from queue
        $btn_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($btn_queue as $key => $log) {
            foreach($log->drivers as $driver) {


                $data = array(
                    'log_id' => substr($log->LogID, -4),
                    'LogID' => $log->LogID,
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' =>empty($driver->truck->plate_number) ? 'NO PLATE' : Truck::callLastTrip($driver->truck->plate_number),
                    'on_serving' =>  Shipment::checkIfShipped($log->CardholderID,null)->first()
                );

                array_push($arr, $data);

            }
        }

        return $arr;
    }

    //  BTN (MGC)  assigned shipment
    public function btnAssignedShipment() 
    {

        // Get drivers with truckscale out within the day
        $check_truckscale_out = Log::btnTruckscaleOut();

        // Get the total drivers who tapped from Gate RFID
        $bataanGate =  Log::barrierLocation(0,9);
    
        // BTN (MGC) queueing location
        $logs = Log::queueLocation(2,7,$check_truckscale_out,$bataanGate,Carbon::today());

        // Get the unique result from queue
        $btn_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($btn_queue as $key => $log) {
            foreach($log->drivers as $driver) {
                if(count($driver->serves->where('created_at','>=',Carbon::today())) != 0) {

                // if(!empty($driver->truck->plate_number)) {
                //     $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                //     $z = str_replace('_','',$x);
                //     $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                // }

                $data = array(
                    'log_id' => substr($log->LogID, -4),
                    'LogID' => $log->LogID,
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' =>empty($driver->truck->plate_number) ? 'NO PLATE' : Truck::callLastTrip($driver->truck->plate_number), 
                    'on_serving' => 1,

                );

                array_push($arr, $data);

                }
            }
        }

        return $arr;
    }

    //  BTN (MGC)  open shipment
    public function btnOpenShipment()
    {
            
        // Get drivers with truckscale out within the day
        $check_truckscale_out = Log::btnTruckscaleOut();

        // Get the total drivers who tapped from Gate RFID
        $bataanGate =  Log::barrierLocation(0,9);
    
        // BTN (MGC) queueing location
        $logs = Log::queueLocation(2,7,$check_truckscale_out,$bataanGate,Carbon::today());

        // Get the unique result from queue
        $btn_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($btn_queue as $key => $log) {
            foreach($log->drivers as $driver) {
                if(count($driver->serves->where('created_at','>=',Carbon::today()))  == 0) {

                // if(!empty($driver->truck->plate_number)) {
                //     $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                //     $z = str_replace('_','',$x);
                //     $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                // }

                $data = array(
                    'log_id' => substr($log->LogID, -4), // $key + 1
                    'LogID' => $log->LogID,
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' =>empty($driver->truck->plate_number) ? 'NO PLATE' : Truck::callLastTrip($driver->truck->plate_number),
                    // 'driver_status' => $driver->availability,
                    'on_serving' => empty($driver->serves->where('created_at','>=',Carbon::today())->first()->on_serving) ? null : $driver->serves->first()->on_serving,

                );

                array_push($arr, $data);

                }
            }
        }

        return $arr;
    }

    // MNL (PFMC) deliveries count
    public function btnGetDeliveriesCount()
    {
        $totalAssiged = count($this->assignedShipment());
        $totalOpen = count($this->openShipment());

        // Get drivers with truckscale out within the day
        $check_truckscale_out = Log::truckscaleOut();

        // Check Trucks who has Truckscale in but not out        
        $check_truckscale_in = Log::trucksInPlant(1,4,$check_truckscale_out)->count();

        $data = array(
            'totalAssigned' => $totalAssiged,
            'totalOpen' => $totalOpen,
            'current_in_plant' => $check_truckscale_in
        );
        return $data;
    }

    

    /**
     * MNL (LAPAZ) Queueing functions
     */

    // Manila LAPAZ Location Method
    public function lpzDeliveries()
    {
        // Get drivers with truckscale out within the day
        $check_truckscale_out = Log::lpzTruckscaleOut();
    
        // MNL (LAPAZ) queueing location
        // Get the total drivers who tapped from Gate RFID
        $lapazGate =  Log::barrierLocation(0,9);
        
        // Gate barrier as temporarily treat as queue
        $logs = Log::queueLocationX(0,5,$check_truckscale_out,Carbon::today());
        $lpz_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($lpz_queue as $key => $log) {
            foreach($log->drivers as $driver) {

                // if(!empty($driver->truck->plate_number)) {
                //     $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                //     $z = str_replace('_','',$x);
                //     $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                // }

                $data = array(
                    'log_id' => substr($log->LogID, -4),
                    'LogID' => $log->LogID,
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' =>empty($driver->truck->plate_number) ? 'NO PLATE' : Truck::callLastTrip($driver->truck->plate_number),
                    // 'driver_status' => $driver->availability,
                    'on_serving' => empty($driver->serves->where('created_at','>=',Carbon::today())->first()->on_serving) ? null : $driver->serves->first()->on_serving,

                );

                array_push($arr, $data);

            }
        }

        return $arr;
    }


     //  MNL (LPZ)  assigned shipment
    public function lpzAssignedShipment() 
    {

        // Get drivers with truckscale out within the day
        $check_truckscale_out = Log::lpzTruckscaleOut();

        //  MNL (LPZ)  queueing location
        $logs = Log::queueLocationX(0,5,$check_truckscale_out,Carbon::today());
        $btn_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($btn_queue as $key => $log) {
            foreach($log->drivers as $driver) {
                if(count($driver->serves->where('created_at','>=',Carbon::today())) != 0) {

                // if(!empty($driver->truck->plate_number)) {
                //     $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                //     $z = str_replace('_','',$x);
                //     $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                // }

                $data = array(
                    'log_id' => substr($log->LogID, -4),
                    'LogID' => $log->LogID,
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' =>empty($driver->truck->plate_number) ? 'NO PLATE' : Truck::callLastTrip($driver->truck->plate_number),
                    'on_serving' => 1,

                );

                array_push($arr, $data);

                }
            }
        }

        return $arr;
    }

    //  MNL (LPZ)  open shipment
    public function lpzOpenShipment()
    {
            
        // Get drivers with truckscale out within the day
        $check_truckscale_out = Log::lpzTruckscaleOut();

        // MNL (LPZ) queueing location
        $logs = Log::queueLocationX(0,5,$check_truckscale_out,Carbon::today());
        $btn_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($btn_queue as $key => $log) {
            foreach($log->drivers as $driver) {
                if(count($driver->serves->where('created_at','>=',Carbon::today()))  == 0) {

                // if(!empty($driver->truck->plate_number)) {
                //     $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                //     $z = str_replace('_','',$x);
                //     $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                // }

                $data = array(
                    'log_id' => substr($log->LogID, -4), // $key + 1
                    'LogID' => $log->LogID,
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' =>empty($driver->truck->plate_number) ? 'NO PLATE' : Truck::callLastTrip($driver->truck->plate_number), 
                    // 'driver_status' => $driver->availability,
                    'on_serving' => empty($driver->serves->where('created_at','>=',Carbon::today())->first()->on_serving) ? null : $driver->serves->first()->on_serving,

                );

                array_push($arr, $data);

                }
            }
        }

        return $arr;
    }

    // MNL (LAPAZ) deliveries count
    public function getLpzDeliveriesCount()
    {
        $totalAssiged = count($this->lpzAssignedShipment());
        $totalOpen = count($this->lpzOpenShipment());

        // Get drivers with truckscale out within the day
        $check_truckscale_out = Log::lpzTruckscaleOut();

        // Check Trucks who has Truckscale in but not out        
        $check_truckscale_in = Log::trucksInPlant(1,6,$check_truckscale_out)->count();

        $data = array(
            'totalAssigned' => $totalAssiged,
            'totalOpen' => $totalOpen,
            'current_in_plant' => $check_truckscale_in
        );
        return $data;
    }


}
