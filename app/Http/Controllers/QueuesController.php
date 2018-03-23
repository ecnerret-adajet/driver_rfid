<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Log;
use App\Driver;
use App\Truck;
use Carbon\Carbon;
use App\Pickup;
use App\Cardholder;
use App\Card;
use App\Serve;
use DB;

class QueuesController extends Controller
{

    public function index()
    {
        return view('queues.index');
    }

    public function pickups()
    {   
        $pickups = Pickup::where('created_at', '>=', Carbon::now()->subDay(3))
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
    
        // MNL (Pfmc) queueing location
        $logs = Log::queueLocation(0,1,$check_truckscale_out,Carbon::today());
        $mnl_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($mnl_queue as $key => $log) {
            foreach($log->drivers as $driver) {

                if(!empty($driver->truck->plate_number)) {
                    $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                    $z = str_replace('_','',$x);
                    $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                }

                $data = array(
                    'log_id' => substr($log->LogID, -4),
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' => empty($y) ? 'UNPROCESS' : $y, 
                    // 'driver_status' => $driver->availability,
                    'on_serving' => empty($driver->serves->where('created_at','>=',Carbon::today())->first()->on_serving) ? null : $driver->serves->first()->on_serving,

                );

                array_push($arr, $data);

            }
        }

        return $arr;
    }

    // MNL (PFMC) assigned shipment
    public function assignedShipment() 
    {

        // Get drivers with truckscale out within the day
        $check_truckscale_out = Log::truckscaleOut();

        // MNL (Pfmc) queueing location
        $logs = Log::queueLocation(0,1,$check_truckscale_out,Carbon::today());
        $mnl_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($mnl_queue as $key => $log) {
            foreach($log->drivers as $driver) {
                if(count($driver->serves->where('created_at','>=',Carbon::today())) != 0) {

                if(!empty($driver->truck->plate_number)) {
                    $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                    $z = str_replace('_','',$x);
                    $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                }

                $data = array(
                    'log_id' => substr($log->LogID, -4),
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' => empty($y) ? 'UNPROCESS' : $y, 
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

        // MNL (Pfmc) queueing location
        $logs = Log::queueLocation(0,1,$check_truckscale_out,Carbon::today());
        $mnl_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($mnl_queue as $key => $log) {
            foreach($log->drivers as $driver) {
                if(count($driver->serves->where('created_at','>=',Carbon::today()))  == 0) {

                if(!empty($driver->truck->plate_number)) {
                    $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                    $z = str_replace('_','',$x);
                    $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                }

                $data = array(
                    'log_id' => substr($log->LogID, -4), // $key + 1
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' => empty($y) ? 'UNPROCESS' : $y, 
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
    
        // BTN (MGC) queueing location
        $logs = Log::queueLocation(2,7,$check_truckscale_out,Carbon::today());
        $btn_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($btn_queue as $key => $log) {
            foreach($log->drivers as $driver) {

                if(!empty($driver->truck->plate_number)) {
                    $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                    $z = str_replace('_','',$x);
                    $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                }

                $data = array(
                    'log_id' => substr($log->LogID, -4),
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' => empty($y) ? 'UNPROCESS' : $y, 
                    // 'driver_status' => $driver->availability,
                    'on_serving' => empty($driver->serves->where('created_at','>=',Carbon::today())->first()->on_serving) ? null : $driver->serves->first()->on_serving,

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

        //  BTN (MGC)  queueing location
        $logs = Log::queueLocation(2,7,$check_truckscale_out,Carbon::today());
        $btn_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($btn_queue as $key => $log) {
            foreach($log->drivers as $driver) {
                if(count($driver->serves->where('created_at','>=',Carbon::today())) != 0) {

                if(!empty($driver->truck->plate_number)) {
                    $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                    $z = str_replace('_','',$x);
                    $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                }

                $data = array(
                    'log_id' => substr($log->LogID, -4),
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' => empty($y) ? 'UNPROCESS' : $y, 
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

        // BTN (MGC) queueing location
        $logs = Log::queueLocation(2,7,$check_truckscale_out,Carbon::today());
        $btn_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($btn_queue as $key => $log) {
            foreach($log->drivers as $driver) {
                if(count($driver->serves->where('created_at','>=',Carbon::today()))  == 0) {

                if(!empty($driver->truck->plate_number)) {
                    $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                    $z = str_replace('_','',$x);
                    $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                }

                $data = array(
                    'log_id' => substr($log->LogID, -4), // $key + 1
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' => empty($y) ? 'UNPROCESS' : $y, 
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
    // public function btnGetDeliveriesCount()
    // {
    //     $totalAssiged = count($this->assignedShipment());
    //     $totalOpen = count($this->openShipment());

    //     // Get drivers with truckscale out within the day
    //     $check_truckscale_out = Log::truckscaleOut();

    //     // Check Trucks who has Truckscale in but not out        
    //     $check_truckscale_in = Log::trucksInPlant(1,4,$check_truckscale_out)->count();

    //     $data = array(
    //         'totalAssigned' => $totalAssiged,
    //         'totalOpen' => $totalOpen,
    //         'current_in_plant' => $check_truckscale_in
    //     );
    //     return $data;
    // }

    

    /**
     * MNL (LAPAZ) Queueing functions
     */

    // Manila LAPAZ Location Method
    public function lpzDeliveries()
    {
        // Get drivers with truckscale out within the day
        $check_truckscale_out = Log::lpzTruckscaleOut();
    
        // MNL (LAPAZ) queueing location
        // Gate barrier as temporarily treat as queue
        $logs = Log::queueLocation(0,5,$check_truckscale_out,Carbon::today());
        $lpz_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($lpz_queue as $key => $log) {
            foreach($log->drivers as $driver) {

                if(!empty($driver->truck->plate_number)) {
                    $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                    $z = str_replace('_','',$x);
                    $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                }

                $data = array(
                    'log_id' => substr($log->LogID, -4),
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' => empty($y) ? 'UNPROCESS' : $y, 
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
        $logs = Log::queueLocation(0,5,$check_truckscale_out,Carbon::today());
        $btn_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($btn_queue as $key => $log) {
            foreach($log->drivers as $driver) {
                if(count($driver->serves->where('created_at','>=',Carbon::today())) != 0) {

                if(!empty($driver->truck->plate_number)) {
                    $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                    $z = str_replace('_','',$x);
                    $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                }

                $data = array(
                    'log_id' => substr($log->LogID, -4),
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' => empty($y) ? 'UNPROCESS' : $y, 
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
        $logs = Log::queueLocation(0,5,$check_truckscale_out,Carbon::today());
        $btn_queue = $logs->unique('CardholderID');
    
        $arr = array();
        
        foreach($btn_queue as $key => $log) {
            foreach($log->drivers as $driver) {
                if(count($driver->serves->where('created_at','>=',Carbon::today()))  == 0) {

                if(!empty($driver->truck->plate_number)) {
                    $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                    $z = str_replace('_','',$x);
                    $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                }

                $data = array(
                    'log_id' => substr($log->LogID, -4), // $key + 1
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'plant_truck' => empty($driver->truck->plants) ? null : $driver->truck->plants->pluck('plant_name'),
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' => empty($y) ? 'UNPROCESS' : $y, 
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
