<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lineup;
use App\Cardholder;
use App\Card;
use Carbon\Carbon;
use App\Log;
use DB;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LineupApiController extends Controller
{
   public function getDriverQue()
   {
        $result_lineups = Log::with(['drivers','drivers.truck','drivers.hauler'])
        ->where('ControllerID', 1)
        ->where('DoorID',0)
        ->whereNotIn('DoorID',['2'])
        ->whereDate('LocalTime', Carbon::now())
        ->orderBy('LogID','DESC')->get();

        $log_lineups = $result_lineups->unique('CardholderID');
    
        $arr = array();

        foreach($log_lineups as $log) {
            foreach($log->drivers as $driver) {

                if(!empty($driver->truck->plate_number)) {
                    $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                    $z = str_replace('_','',$x);
                    $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                }

                $data = array(
                    'driver_avatar' => $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' => empty($y) ? 'UNPROCESS' : $y, 
                    'driver_status' => $driver->availability
                );

                array_push($arr, $data);

            }
        }

        return $arr;
   }

}
