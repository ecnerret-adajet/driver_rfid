<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessGateEntries;
use App\Events\GateEntryEvent;
use Auth;
use App\GateEntry;
use App\Log;
use App\Driverqueue;
use Carbon\Carbon;
use App\Shipment;
use App\Cardholder;

class GateEntriesController extends Controller
{
    // Test Job Queue Worker
    public function processGateEntries() {
           // Create podcast...
         dispatch(new ProcessGateEntries());

         return 'Dispatched Successfully';
    }

    // Capture All RFIC Cards which no driver assigned
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

    // Store new gate entry by location
    public function storeGateEntries(Request $request, $driverqueue_id) 
    {

        $driverLocation = Driverqueue::where('id',$driverqueue_id)->first();

        $lastLogEntry = Log::where('DoorID',$driverLocation->gate->door)
                        ->where('ControllerID', $driverLocation->gate->controller)
                        ->whereNotIn('CardholderID',$this->barrierNoDriver())
                        ->where('CardholderID', '>=', 15)
                        ->orderBy('LocalTime','DESC')
                        ->with('driver','driver.image','driver.truck','driver.hauler','shipment')
                        ->first();

        $totalEntry = GateEntry::whereDate('created_at',Carbon::today())->where('driverqueue_id',$driverLocation->id)->count();                

        $gateEntry = GateEntry::firstOrCreate(
            [
                'LogID' => $lastLogEntry->LogID,
            ],
            [
                // 'shipment_number' => Shipment::checkIfShipped($lastLogEntry->CardholderID,null)->first(),
                // 'shipment_number' => Shipment::getShipment($lastLogEntry->LogID),
                'isShipmentStarted' => 0,
                'driver_availability' => !empty($lastLogEntry->driver) && $lastLogEntry->driver->availability == 1 ? 1 : null,
                'truck_availability' =>  !empty($lastLogEntry->driver->truck) && $lastLogEntry->driver->truck->availability  == 1 ? 1 : null,
                'access_location' =>  !empty($lastLogEntry->driver->truck) ? $lastLogEntry->driver->truck->access_location : null,
                'CardholderID' => $lastLogEntry->CardholderID,
                'gate_number' =>  $totalEntry + 1 ."-". $driverLocation->id,
                'driver_name' => $lastLogEntry->driver->name,
                'avatar' =>   !empty($lastLogEntry->driver->image->avatar) ? $lastLogEntry->driver->image->avatar : $lastLogEntry->driver->avatar,
                'plate_number' => !empty($lastLogEntry->driver->truck) ? $lastLogEntry->driver->truck->plate_number : "NO PLATE NUMBER",
                'hauler_name' => !empty($lastLogEntry->driver->name) ? $lastLogEntry->driver->hauler->name : "NO HAULER",
                'driverqueue_id' => $driverLocation->id,
                'LocalTime' => $lastLogEntry->LocalTime,
            ]
        );

        if($gateEntry->wasRecentlyCreated == true) {

            $gateEntry->shipment_number = Shipment::getShipment($lastLogEntry->LogID);
            $gateEntry->save();

            return $gateEntry;

        } else {
            $last = GateEntry::where('driverqueue_id',$driverqueue_id)
                            ->orderBy('id','DESC')
                            ->first();
                
            return $last; 
        }

    }

    // Get the last gate entry by location
    public function getLastGateEntry($driverqueue_id) 
    {

        $lastEntry = GateEntry::orderBy('id','DESC')
                    ->where('driverqueue_id',$driverqueue_id)->first();
    
        return $lastEntry;

    }

    // Display gate entry by location
    public function gateEntry(Driverqueue $driverqueue) 
    {

        return view('gateEntries.show',compact('driverqueue'));

    }

}
