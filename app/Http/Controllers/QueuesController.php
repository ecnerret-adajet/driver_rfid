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
use DB;

class QueuesController extends Controller
{

    public function index()
    {
        return view('queues.index');
    }

    public function pickups()
    {
        // $pickups = Pickup::where('created_at', '>=', Carbon::now()->subDay())
        //                         ->orderBy('created_at','DESC')
        //                         ->with('cardholder','user')
        //                         ->
        //                         ->get();
        
        $pickups = Pickup::where('created_at', '>=', Carbon::now()->subDay(3))
                    ->orderBy('created_at','DESC')
                    // ->whereNull('cardholder_id')
                    ->with('cardholder','user')
                    ->get();

        return $pickups;

    }


    public function deliveries()
    {
        $check_truckscale_out = Log::select('CardholderID')
                                    ->where('ControllerID', 4)
                                    ->where('Direction',2)
                                    ->whereDate('LocalTime', Carbon::now())
                                    ->pluck('CardholderID');

        $result_lineups = Log::with(['drivers','drivers.truck','drivers.hauler','driver.serves'])
        ->where('ControllerID', 1)
        ->where('DoorID',0)
        ->whereNotIn('CardholderID',$check_truckscale_out)
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
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' => empty($y) ? 'UNPROCESS' : $y, 
                    // 'driver_status' => $driver->availability,
                    'on_serving' => empty($driver->serves->first()->on_serving) ? null : $driver->serves->first()->on_serving,

                );

                array_push($arr, $data);

            }
        }

        return $arr;
    }
}
