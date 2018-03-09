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

    public function getLastDriver()
    {

        // Get the total truckscale Out from truck monitoring today
        $check_truckscale_out = Log::truckscaleOut();
        // Get the total served from truck monitoring today
        $served = Serve::servedToday();

         $result_lineups = Log::with(['drivers','drivers.trucks','drivers.haulers','driver.serves'])
                        ->where('ControllerID', 1)
                        ->where('DoorID',0)
                        ->whereNotIn('CardholderID',$check_truckscale_out)
                        ->whereDate('LocalTime', Carbon::now())
                        ->orderBy('LogID','DESC')
                        ->take(1)
                        ->get();

        $log_lineups = $result_lineups->unique('CardholderID');

        
    
        $arr = array();

        foreach($log_lineups as $log) {
            foreach($log->drivers->whereNotIn('id', $served) as $driver) {

                    if(!empty($driver->trucks)) {
                        $x = str_replace('-',' ',strtoupper($driver->trucks->first()->plate_number));
                        $z = str_replace('_','',$x);
                        $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                        if(!empty($y)) {
                            $a = $y[0];
                        }
                    }

                    $data = array(
                        'queue_number' => substr($log->LogID,-4),
                        'driver_id' => $driver->id,
                        'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                        'driver_name' => $driver->name,
                        'plate_number' => empty($driver->trucks->first()->plate_number) ? 'NO PLATE' : $driver->trucks->first()->plate_number,
                        'hauler' => empty($driver->haulers->first()->name) ? 'NO HAULER' : $driver->haulers->first()->name,
                        'log_time' => $log->LocalTime,
                        'dr_status' => empty($y) ? 'UNPROCESS' : $a, 
                        'on_serving' => empty($driver->serves->first()->on_serving) ? null : $driver->serves->first()->on_serving,
                        );
                        
                    
                    
                    array_push($arr, $data);
                    
            }
        }

        return $arr;
   }

    // MANILA PLFC Driver's queue
    public function getDriverQue()
    {
        // Get the total truckscale Out from truck monitoring today
        $check_truckscale_out = Log::truckscaleOut();
        // Get the total served from truck monitoring today
        $served = Serve::servedToday();
        // Get the total drivers who tapped from Gate RFID
        $manilaGate =  Log::barrierLocation(3,2);

        $result_lineups = Log::with(['drivers','drivers.truck','drivers.hauler','driver.serves','driver.image'])
                        ->where('ControllerID', 1)
                        ->where('DoorID',0)
                        ->whereIn('CardholderID',$manilaGate)
                        ->whereNotIn('CardholderID',$check_truckscale_out)
                        ->whereDate('LocalTime', Carbon::now())
                        ->orderBy('LogID','ASC')
                        ->take(20)
                        ->get();

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
                        'log_time' => $log->LocalTime,
                        'dr_status' => empty($y) ? 'UNPROCESS' : $a, 
                        'on_serving' => empty($driver->serves->first()->on_serving) ? null : $driver->serves->first()->on_serving,
                        );
                        
                    
                    
                    array_push($arr, $data);
                    
            }
        }

        return $arr;
   }

}
