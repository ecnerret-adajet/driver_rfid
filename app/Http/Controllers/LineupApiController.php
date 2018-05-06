<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lineup;
use App\Cardholder;
use App\Card;
use Carbon\Carbon;
use App\Log;
use DB;
use App\Serve;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Driverqueue;

class LineupApiController extends Controller
{

    public function getTotalQueueToday()
    {      
        // Get the total truckscale Out from truck monitoring today
        $check_truckscale_out = Log::truckscaleOut();

        // Check Trucks who has Truckscale in but not out        
        $check_truckscale_in = Log::whereNotIn('CardholderID',$check_truckscale_out)
                        ->truckscaleIn()
                        ->count();

        // Get all drivers count who tapped from queue reader without out
         $result_lineups = Log::with(['drivers','drivers.truck','drivers.hauler','driver.serves'])
                        ->where('ControllerID', 1)
                        ->where('DoorID',0)
                        ->whereNotIn('CardholderID',$check_truckscale_out)
                        ->whereDate('LocalTime', Carbon::now())
                        ->orderBy('LogID','DESC')
                        ->get();
                        
        // Get unique from results_lineup
        $log_lineups = $result_lineups->unique('CardholderID');

        $arr = array();

        foreach($log_lineups as $log) {
            foreach($log->drivers as $driver) {
                
                    $data = array(
                        'driver_id' => $driver->id,
                        );
                                            
                    array_push($arr, $data);             

            }
        }

        $total_array = array(
            'total' => count($arr),
            'current_in_plant' => $check_truckscale_in
        );

        return $total_array;

    }

    
    public function conditionFromLastDriver($driverqueue)
    {   
        $queue = Driverqueue::where('id',$driverqueue)->first();

        $checkLastDriver = Log::lastDriver($queue->door,$queue->controller); // returns object, last item

        $lastDriver = Log::lastDriverCardholder($queue->door, $queue->controller); // return pluck, last item
        $check_truckscale_out = Log::truckscaleOutLocation($queue->ts_out_controller); // return pluck, all truckscal out
        $check_truckscale_in = Log::barrierLocation($queue->gate->door,$queue->gate->controller); // return pluck, all truckscale In

        // if driver has oustanding DR
        $plate = $checkLastDriver->first()->drivers->first()->truck->platenum_format;
        $outstandingDR = DB::connection('dr_fp_database')
                        ->select("CALL P_LAST_TRIP('$plate','deploy')");

        // Driver didn't tap to gate first
        $mainGate = Log::whereIn('CardholderID',[$lastDriver])->barrierLocation($queue->gate->door,$queue->gate->controller);

        // Check if driver has in and out within manila plant in a current day
        // $tapComplete = array_collapse([[$check_truckscale_in], [$check_truckscale_out]]);
        // $tapComplete = array_collapse([$check_truckscale_in, $check_truckscale_out]);
        // $checkTapComplete = Log::whereIn('CardholderID',$tapComplete)->lastDriver($queue->door,$queue->controller);         

        // Check if driver or truck is deactivated
        $isDriverActivated = $checkLastDriver->first()->drivers->first()->availability;
        $isTruckActivated = $checkLastDriver->first()->drivers->first()->truck->availability;


        if(count($outstandingDR) == 0) {
            return array(
                'status' => 'table-danger',
                'message' => 'Please submit/complete outstanding DR first, then tap again!',
                'code' => 1
            );
        } elseif (count($mainGate) == 0) {
             return array(
                'status' => 'table-danger',
                'message' => 'Tap first to main gate RFID, in order to reserve a queue slot',
                'code' => 2
            );
        } elseif ($isDriverActivated == 0) {
            return array(
                'status' => 'table-danger',
                'message' => 'Driver is deactivated!',
                'code' => 3
            );
        } elseif ($isTruckActivated == 0) {
            return array(
                'status' => 'table-danger',
                'message' => 'Truck is deactivated!',
                'code' => 4
            );
        } else {

            // $generateQueue = GenerateQueue::firstOrCreate(
            //     ['LogID' => $checkLastDriver->LogID],
            //     [
            //         'CardholderID' => $checkLastDriver->CardholderID,
            //         'queue_number' =>  Carbon::today()->format("w").
            //                             Carbon::today()->format("m").
            //                             Carbon::today()->format("y").'-',
            //                             // count_current_queue + 1 // with area code
            //         'driver_id' => $checkLastDriver->first()->driver->id,
            //         'truck_id' => $checkLastDriver->first()->driver->truck->id,
            //         'hauler_id' =>$checkLastDriver->first()->driver->hauler->id,
            //         'queue_date' => Carbon::now(),
            //     ]
            // );

             return array(
                'status' => 'table-success',
                'message' => 'Added to queue successfully!',
                'code' => 5
            );
        }

    }

    public function getLastDriver()
    {
        $lastDriver = Log::lastDriver(0,1);
        
        return $lastDriver;
    }

    // MANILA PLFC Driver's queue
    public function getDriverQue()
    {
        // Get the total truckscale Out from truck monitoring today
        $check_truckscale_out = Log::truckscaleOutRecent();

        // Get the total served from truck monitoring today
        $served = Serve::servedToday();

        // Get the total drivers who tapped from Gate RFID
        $manilaGate =  Log::barrierLocationRecent(3,2);

        // Get the queue result
        $result_lineups = Log::driverQueueingLocation(1, 0, $manilaGate, $check_truckscale_out);

        // Get the unique result from Cardholder
        $log_lineups = $result_lineups->unique('CardholderID');

    
        $arr = array();

        foreach($log_lineups as $key => $log) {
            foreach($log->drivers->whereNotIn('id', $served) as  $driver) {



                    if(!empty($driver->truck->plate_number)) {
                        $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                        $z = str_replace('_','',$x);
                        $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                        if(!empty($y)) {
                            $a = $y[0];
                        }                    
                    }


                    $data = array(
                        'queue_number' => substr($log->LogID,-4),
                        'driver_id' => $driver->id,
                        'driver_avatar' => empty($driver->image) ? $driver->avatar : $driver->image->avatar,
                        'driver_name' => $driver->name,
                        'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                        'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                        'availability' => $driver->availability,
                        'log_time' => $log->LocalTime,
                        'dr_status' => empty($y) ? 'UNPROCESS' : $a, 
                        'on_serving' => empty($driver->serves->first()->on_serving) ? null : $driver->serves->first()->on_serving,
                        );
                        
                    
                    
                    array_push($arr, $data);
                    
            }
        }

        return $arr;
   }

    /**
     * 
     *  BATAAN QUEUEING MONITOR
     * 
     */
   
   // BTN MGC Driver's queue
    public function getBtnDriverQue()
    {
        // Get the total truckscale Out from truck monitoring today
        $check_truckscale_out = Log::btnTruckscaleOutRecent();

        // Get the total served from truck monitoring today
        $served = Serve::servedToday();

        // Get the total drivers who tapped from Gate RFID
        $bataanGate =  Log::barrierLocationRecent(0,9);
        
        // Get Driver queueing location (controller, door, gate, TS_OUT)
        $result_lineups = Log::driverQueueingLocation(7, 2, $bataanGate, $check_truckscale_out);

        // Get the unique result from Cardholder
        $log_lineups = $result_lineups->unique('CardholderID');

    
        $arr = array();

        foreach($log_lineups as $key => $log) {
            foreach($log->drivers->whereNotIn('id', $served) as  $driver) {


                    if(!empty($driver->truck->plate_number)) {
                        $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                        $z = str_replace('_','',$x);
                        $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                        if(!empty($y)) {
                            $a = $y[0];
                        }                    
                    }

                    $data = array(
                        'queue_number' => substr($log->LogID,-4),
                        'driver_id' => $driver->id,
                        'driver_avatar' => empty($driver->image) ? $driver->avatar : $driver->image->avatar,
                        'driver_name' => $driver->name,
                        'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                        'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                        'availability' => $driver->availability,
                        'log_time' => $log->LocalTime,
                        'dr_status' => empty($y) ? 'UNPROCESS' : $a, 
                        'on_serving' => empty($driver->serves->first()->on_serving) ? null : $driver->serves->first()->on_serving,
                        );
                        
                    
                    
                    array_push($arr, $data);
                    
            }
        }

        return $arr;
   }

    public function getBtnTotalQueueToday()
    {      
        // Get the total truckscale Out from truck monitoring today
        $check_truckscale_out = Log::btnTruckscaleOut();

        // Check Trucks who has Truckscale in but not out        
        $check_truckscale_in = Log::whereNotIn('CardholderID',$check_truckscale_out)
                        ->btnTruckscaleIn()
                        ->count();

        // Get all drivers count who tapped from queue reader without out
         $result_lineups = Log::with(['drivers','drivers.truck','drivers.hauler','driver.serves'])
                        ->where('ControllerID', 7)
                        ->where('DoorID',2)
                        ->whereNotIn('CardholderID',$check_truckscale_out)
                        ->whereDate('LocalTime', Carbon::today())
                        ->orderBy('LogID','DESC')
                        ->get();
                        
        // Get unique from results_lineup
        $log_lineups = $result_lineups->unique('CardholderID');

        $arr = array();

        foreach($log_lineups as $log) {
            foreach($log->drivers as $driver) {
                
                    $data = array(
                        'driver_id' => $driver->id,
                        );
                                            
                    array_push($arr, $data);             

            }
        }

        $total_array = array(
            'total' => count($arr),
            'current_in_plant' => $check_truckscale_in
        );

        return $total_array;

    }

    public function getBtnLastDriver()
    {
        $lastDriver = Log::lastDriver(2,7);
       
        return $lastDriver;
    }

}
