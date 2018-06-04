<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
use App\Driver;
use App\Cardholder;
use Carbon\Carbon;
use App\Driverqueue;
use App\GateEntry;
use App\QueueEntry;
use App\Pickup;

class StatusesController extends Controller
{
    public function totalTrucksInPlant()
    {

       $totalEntry = $this->gateEntries();

        $queueObject = array();

        $gate_count = array(
            'total_count' => $totalEntry
        );
        array_push($queueObject, $gate_count);
        
        $driverqueues = Driverqueue::all();
        foreach ($driverqueues as $key => $driverqueue) {

            $location_count = $this->gateEntries($driverqueue->id);
            
            $data = array(
                $driverqueue->title => $location_count
            );
            
            array_push($queueObject,$data);
        }

        return $queueObject;

    }

    public function totalAssignedShipment()
    {

        $queueObject = array();

        $totalShipment = array(
            'total_assigned' => $this->queueEntries()
        );
        array_push($queueObject, $totalShipment);

        $driverqueues = Driverqueue::all();
        
        foreach ($driverqueues as $key => $driverqueue) {

            $data = array (
                $driverqueue->title => $this->queueEntries($driverqueue->id)
            );

            array_push($queueObject,$data);
        }

       
       
        return $queueObject;

    }

    public function totalForPrint()
    {
        return $this->forPrint();
    }

    public function totalPickup()
    {
        $served = $this->pickupEntries()
                    ->whereNotNull('cardholder_id')
                    ->whereNotNull('deactivated_date')
                    ->count();

        $inPlant = $this->pickupEntries()
                    ->whereNotNull('cardholder_id')
                    ->whereNull('deactivated_date')
                    ->count();

        return array(
            'served' => $served,
            'in_plant' => $inPlant
        );
        
    }


    /**
     * Get and count total number of pickup served within a day
     */
    public function pickupEntries()
    {
         $pickups = Pickup::with('cardholder','user')
                        ->orderBy('id','DESC')
                        ->whereDate('activation_date', Carbon::today());
        return $pickups;
    }

    /**
     * Get drivers fro printing option
     */
    public function forPrint()
    {
        $prints = Driver::with('image','truck:plate_number','hauler:name')
                    ->where('print_status',1)
                    ->orderBy('id','DESC')    
                    ->get();
        
        return $prints;
    }

    /**
     *  Count the total driver entries in gate plant in RFID with the given location id or arrays of id
     */
    public function gateEntries($driverqueues = null) 
    {
        $checkDriverqueue =  !empty($driverqueues) ? array($driverqueues) : Driverqueue::pluck('id');

        $checkTruckscaleOut = collect(Log::truckscaleOutQueueArray())->unique();

        $gate = GateEntry::whereIn('driverqueue_id',$checkDriverqueue)
                        ->whereDate('LocalTime', Carbon::today())
                        ->whereNotIn('CardholderID',$checkTruckscaleOut->values()->all())
                        ->get()
                        ->unique('CardholderID')
                        ->count();
        
        return $gate;

    }

    /**
     * Count the total number of assigned shipment within the day in all locations
     */
    public function queueEntries($driverqueues = null) 
    {

        $checkDriverqueue =  !empty($driverqueues) ? array($driverqueues) : Driverqueue::pluck('id');

        $queues = QueueEntry::whereIn('driverqueue_id',$checkDriverqueue)
                            ->whereDate('created_at', Carbon::today())
                            ->has('shipment')
                            ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                            ->whereNotNull('isTappedGateFirst')
                            ->get()
                            ->unique('CardholderID')
                            ->count();

        return $queues;
    }



}
