<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Card;
use App\Cardholder;
use App\Log;
use App\Driver;
use Carbon\Carbon;

class BarriersController extends Controller
{

    // Capture All RFIC Cards that has not a driver CARD
    public function barrierNoDriver()
    {
        $pickup_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%pickup%')
        ->pluck('CardholderID'); 

        $guard_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%GUARD%')
        ->pluck('CardholderID'); 

        $executive_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%EXECUTIVE%')
        ->pluck('CardholderID'); 

        // Remove all cardholder without driver assigned
        $not_driver = array_collapse([$pickup_cards, $guard_cards, $executive_cards]);
        
        return $not_driver;
    }

    // check the direction of the barrier
    public function getBarrierDirection($door, $cardholder, $direction)
    {
        // All Plant in 
        $barrier_in = Log::select('CardholderID','Direction','LocalTime')
        ->where('CardholderID',$cardholder)
        ->where('DoorID',$door)
        ->whereNotIn('CardholderID',$this->barrierNoDriver())
        ->where('CardholderID', '>=', 15)
        ->where('Direction', $direction)
        ->orderBy('LocalTime','DESC')
        ->first();

        if(empty($barrier_in)) {
            $x = null;
        } else {
            $x = $barrier_in->LocalTime;
        }

        return $x;
    }

    public function getBarrierLocation($door, $controller)
    {

        $barriers = Log::select('LogID','CardholderID')
        // ->whereDate('LocalTime',Carbon::today())
        ->whereIn('DoorID',[$door])
        ->whereNotIn('CardholderID',$this->barrierNoDriver())
        ->where('ControllerID', $controller)
        ->where('CardholderID', '>=', 15)
        ->orderBy('LocalTime','DESC')
        ->with('driver')
        ->take(10)
        ->get();

        return $barriers;
    }

    //API Functions
    public function laPazAPI()
    {
        // Get Logs from Lapaz Barrier RFID
        $lapaz_drivers = $this->getBarrierLocation(0,5);

        // Format the array JSON return
        $arr = array();
        foreach($lapaz_drivers as $entry) {
            foreach($entry->drivers as $driver) {

                    $data = array(

                        'LogID' => $entry->LogID,
                        'CardholderID' => $entry->CardholderID,
                        'driver' => $driver->name,
                        'availability' => $driver->availability,
                        'avatar' => empty($driver->image) ?  $driver->avatar : $driver->image->avatar,
                        'plate_number' => empty($driver->truck->plate_number) ? 'NO DRIVER' : $driver->truck->plate_number,
                        'plate_availability' => empty($driver->truck->plate_number) ? null : $driver->truck->availability,
                        'hauler_name' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                        'inLocalTime' =>  $this->getBarrierDirection(0 ,$entry->CardholderID, 1),
                        'outLocalTime' =>  $this->getBarrierDirection(0, $entry->CardholderID, 2) < 
                                            $this->getBarrierDirection(0, $entry->CardholderID, 1) ? null : 
                                            $this->getBarrierDirection(0, $entry->CardholderID, 2),
                    );

                    array_push($arr, $data);
            }
        }

        return $arr;
    }

    public function manilaAPI()
    {
        // Get Logs from Lapaz Barrier RFID
        $manila_drivers = $this->getBarrierLocation(3,2);

        // Format the array JSON return
        $arr = array();
        foreach($manila_drivers as $entry) {
            foreach($entry->drivers as $driver) {

                    $data = array(

                        'LogID' => $entry->LogID,
                        'CardholderID' => $entry->CardholderID,
                        'driver' => $driver->name,
                        'availability' => $driver->availability,
                        'avatar' => empty($driver->image) ?  $driver->avatar : $driver->image->avatar,
                        'plate_number' => empty($driver->truck->plate_number) ? 'NO DRIVER' : $driver->truck->plate_number,
                        'plate_availability' => empty($driver->truck->plate_number) ? null : $driver->truck->availability,
                        'hauler_name' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                        'inLocalTime' =>  $this->getBarrierDirection(3 ,$entry->CardholderID, 1),
                        'outLocalTime' =>  $this->getBarrierDirection(3, $entry->CardholderID, 2) < 
                                            $this->getBarrierDirection(3, $entry->CardholderID, 1) ? null : 
                                            $this->getBarrierDirection(3, $entry->CardholderID, 2),
                        'isFromLapaz' => array_has($entry->CardholdereID, Log::barrierLocation(0,5)) ? 1 : null,

                    );

                    array_push($arr, $data);
            }
        }

        return $arr;
    }

    public function bataanAPI()
    {
         // Get Logs from Bataan Barrier RFID
        $bataan_drivers =  $this->getBarrierLocation(0,9);

        // Format the array JSON return
        $arr = array();
        foreach($bataan_drivers as $entry) {
            foreach($entry->drivers as $driver) {

                    $data = array(

                        'LogID' => $entry->LogID,
                        'CardholderID' => $entry->CardholderID,
                        'driver' => $driver->name,
                        'availability' => $driver->availability,
                        'avatar' => empty($driver->image) ?  $driver->avatar : $driver->image->avatar,
                        'plate_number' => empty($driver->truck->plate_number) ? 'NO DRIVER' : $driver->truck->plate_number,
                        'plate_availability' => empty($driver->truck->plate_number) ? null : $driver->truck->availability,
                        'hauler_name' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                        'inLocalTime' =>  $this->getBarrierDirection(0 ,$entry->CardholderID, 1),
                        'outLocalTime' =>  $this->getBarrierDirection(0, $entry->CardholderID, 2) < 
                                            $this->getBarrierDirection(0, $entry->CardholderID, 1) ? null : 
                                            $this->getBarrierDirection(0, $entry->CardholderID, 2),
                    );

                    array_push($arr, $data);
            }
        }

        return $arr;
    }

    // View Functions
    public function laPazArea()
    {
        return view('locations.lapaz');
    }

    public function manilaArea()
    {
        return view('locations.manila');
    }

    public function bataanArea()
    {
        return view('locations.bataan');
    }

}
