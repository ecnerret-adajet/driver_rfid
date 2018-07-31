<?php

namespace App\Http\Controllers;

use App\Transformers\QueueEntriesTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Traits\NotDriverTrait;
use App\Traits\QueueTrait;
use App\Events\QueueEntryEvent;
use App\Driverqueue;
use App\QueueEntry;
use App\GateEntry;
use App\Truck;
use App\Driver;
use App\Log;
use App\Cardholder;
use Carbon\Carbon;
use App\Shipment;
use Auth;
use DB;
use Session;

class QueueEntriesController extends Controller
{
    use NotDriverTrait, QueueTrait;

    public function __construct()
    {
        $this->notDriver();
    }

    // Show all recently queue
    public function getQueueEntries($driverqueue_id)
    {
        $checkTruckscaleOut = collect(Log::truckscaleOutFromQueue($driverqueue_id))->unique();

        $queues = QueueEntry::where('driverqueue_id',$driverqueue_id)
                            ->whereNotIn('CardholderID',$checkTruckscaleOut->values()->all())
                             ->where('created_at', '>=', Carbon::today())
                            // ->whereNull('shipment_number')
                            ->doesntHave('shipment')
                            ->whereNotNull('driver_availability')
                            ->whereNotNull('truck_availability')
                            ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                            ->whereNotNull('isTappedGateFirst')
                            ->orderBy('LocalTime','ASC')
                            ->get()
                            ->unique('CardholderID');

        return $queues->values()->all();
    }

    /**
     * Display driver entry logs from queue RFID station
     *
     * @param App\Driverqueue $driverqueue_id
     * @param date $date
     * @return json
     */
    public function getQueueEntriesJson($driverqueue_id,$date)
    {

        Session::put('queueDate', Carbon::parse($date));
        $dateSearch = Session::get('queueDate');

        $queues = QueueEntry::with('truck','truck.plants:plant_name','truck.capacity','shipment')
                            ->whereDate('created_at',$dateSearch)
                            ->where('driverqueue_id',$driverqueue_id)
                            // ->whereNotIn('CardholderID',$checkTruckscaleOut->values()->all())
                            ->whereNotNull('driver_availability')
                            ->whereNotNull('truck_availability')
                            ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                            ->whereNotNull('isTappedGateFirst')
                            ->orderBy('LocalTime','ASC')
                            ->get()
                            ->unique('CardholderID')
                            ->values()->all();

            $manager = new Manager();
            $resource = new Collection($queues, new QueueEntriesTransformer());

            return $manager->createData($resource)->toArray();
    }

    // Show all queue to feed planner's monitoring
    public function getQueueEntriesFeed($driverqueue_id)
    {
        // $checkTruckscaleOut = collect(Log::truckscaleOutFromQueue($driverqueue_id))->unique();

        $queues = QueueEntry::with('truck','truck.plants:plant_name','truck.capacity','shipment')
                            ->whereDate('created_at',Carbon::today())
                            ->where('driverqueue_id',$driverqueue_id)
                            // ->whereNotIn('CardholderID',$checkTruckscaleOut->values()->all())
                            ->whereNotNull('driver_availability')
                            ->whereNotNull('truck_availability')
                            ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                            ->whereNotNull('isTappedGateFirst')
                            ->orderBy('LocalTime','ASC')
                            ->get()
                            ->unique('CardholderID');

        return $queues->values()->all();
    }

    /**
     * Shows all queue to feed planners monitoring - for eexpired within 24 hours
     *
     * @param App\Driverqueue $driverqueue_id
     * @return json
     */
    public function getQueueFromCreatedDate($driverqueue_id)
    {

        Session::put('queueDate', Carbon::now()->subDay());
        $dateSearch = Session::get('queueDate');

         $queues = QueueEntry::whereDate('LocalTime', '>=', $dateSearch)
                            ->where('driverqueue_id',$driverqueue_id)
                            // ->whereNotIn('CardholderID',$checkTruckscaleOut->values()->all())
                            ->whereNotNull('driver_availability')
                            ->whereNotNull('truck_availability')
                            ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                            ->whereNotNull('isTappedGateFirst')
                            ->orderBy('LocalTime','DESC')
                            ->with('truck','truck.plants:plant_name','truck.capacity','shipment')
                            ->get()
                            ->unique('CardholderID');

        return $queues->values()->all();

    }

    //Show all expired queues older than 24 hours
    public function expiredQueues($driverqueue_id)
    {

        $last_entry = end($this->searchQueueEntriesFeed($driverqueue_id)['data'])['LocalTime'];
        $olderDate = Carbon::now()->subDays(3);

        // Session::put('queueDate', Carbon::now()->subHours(24));
        // Session::put('queueDate', Carbon::now()->subDays(2)->subHours(24));

        $queues = QueueEntry::where('LocalTime', '>=', $olderDate)
                            ->where('LocalTime', '<', $last_entry)
                            ->where('driverqueue_id',$driverqueue_id)
                            // ->whereNotIn('CardholderID',$checkTruckscaleOut->values()->all())
                            // ->doesntHave('shipment')
                            ->whereNotNull('driver_availability')
                            ->whereNotNull('truck_availability')
                            ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                            ->whereNotNull('isTappedGateFirst')
                            ->orderBy('LocalTime','DESC')
                            ->with('truck','truck.plants:plant_name','truck.capacity','shipment')
                            ->get()
                            ->unique('CardholderID');

        return $queues->values()->all();
    }

    //Search queue entries by date
    public function searchQueueEntriesFeed($driverqueue)
    {
        // Session::put('queueDate', $search_date);
        Session::put('queueDate', Carbon::now()->subDay());
        $dateSearch = Session::get('queueDate');

        // $checkTruckscaleOut = collect(Log::truckscaleOutFromQueue($driverqueue_id))->unique();

        $queues = QueueEntry::with('truck','truck.plants:plant_name','truck.capacity','qshipment')
                            ->where('LocalTime',  '>=', $dateSearch) // get less than 24 hours from tap
                            ->where('driverqueue_id',$driverqueue)
                            // ->whereNotIn('CardholderID',$checkTruckscaleOut->values()->all())
                            ->whereNotNull('driver_availability')
                            ->whereNotNull('truck_availability')
                            ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                            ->whereNotNull('isTappedGateFirst')
                            ->orderBy('LocalTime','DESC')
                            ->get()
                            ->unique('CardholderID')
                            ->values()->all();

        $manager = new Manager();
        $resource = new Collection($queues, new QueueEntriesTransformer());

        return $manager->createData($resource)->toArray();
    }

    //Count Queue Statuses
    public function getQueueStatus($driverqueue)
    {
        $totalAssigned = QueueEntry::totalAssigned($driverqueue)->count();
        $totalOpen = QueueEntry::totalOpen($driverqueue)->count();
        $current_in_plant = count($this->getQueueEntriesFeed($driverqueue));

        $data = array(
            'totalAssigned' => $totalAssigned,
            'totalOpen' => $totalOpen,
            'current_in_plant' => $current_in_plant
        );

        return $data;
    }

    //Get the last tapped driver to planners monitoring
    public function lastDriverTapped($driverqueue)
    {
        $lastDriverTapped = QueueEntry::orderBy('id','DESC')
                        ->has('shipment')
                    //    ->whereNotNull('shipment_number')
                        ->where('driverqueue_id',$driverqueue)
                        ->first();

        return $lastDriverTapped;
    }

    // Store new queue entry
    public function storeQueueEntries(Request $request, $driverqueue_id)
    {

        $driverLocation = Driverqueue::where('id',$driverqueue_id)->first();

        $lastLogEntry = Log::where('DoorID',$driverLocation->door)
                        ->where('ControllerID', $driverLocation->controller)
                        ->whereNotIn('CardholderID',$this->notDriver())
                        ->where('CardholderID', '>=', 15)
                        ->orderBy('LocalTime','DESC')
                        ->with('driver','driver.image','driver.truck','driver.hauler')
                        ->first();

        $queueEntry = QueueEntry::firstOrCreate(
            [
                'LogID' => $lastLogEntry->LogID,
            ],
            [
                'shipment_number' => Shipment::checkIfShipped($lastLogEntry->CardholderID,null)->first(),
                'driver_name' => $lastLogEntry->driver->name,
                'plate_number' => !empty($lastLogEntry->driver->truck) ? $lastLogEntry->driver->truck->plate_number : null,
                'hauler_name' => !empty($lastLogEntry->driver->hauler) ? $lastLogEntry->driver->hauler->name : null,
                'CardholderID' => $lastLogEntry->CardholderID,
                'queue_number' => $this->checkIfExist($driverLocation->id),
                'isDRCompleted' =>  !empty($lastLogEntry->driver->truck) ? Truck::callLastTrip($lastLogEntry->driver->truck->plate_number) : null,
                'isTappedGateFirst' => !empty(GateEntry::checkIfTappedFromGate($lastLogEntry->CardholderID)) ? 1 : null,
                'isSecondDelivery' => $this->checkIfReturned($lastLogEntry->CardholderID) > 0 ? 1 : 0,
                'driver_availability' => !empty($lastLogEntry->driver) && $lastLogEntry->driver->availability == 1 ? 1 : null,
                'truck_availability' => !empty($lastLogEntry->driver->truck) && $lastLogEntry->driver->truck->availability == 1 ? 1 : null,
                'isShipmentStarted' => 0,
                'truck_id' => !empty($lastLogEntry->driver->truck) ? $lastLogEntry->driver->truck->id : null,
                'avatar' => !empty($lastLogEntry->driver->image) ? $lastLogEntry->driver->image->avatar : $lastLogEntry->driver->avatar,
                'driverqueue_id' => $driverLocation->id,
                'LocalTime' => $lastLogEntry->LocalTime,
            ]
        );

        if($queueEntry->wasRecentlyCreated == true) {
            event(new QueueEntryEvent($queueEntry,$driverLocation));
            return $queueEntry;
        } else {
            $queueLast = QueueEntry::where('driverqueue_id',$driverLocation->id)->orderBy('id','DESC')->first();
            return $queueLast;
        }

    }

    //Display queue entry by location
    public function queueEntry(Driverqueue $driverqueue)
    {
        return view('queueEntries.show',compact('driverqueue'));
    }

    //Display queue from planners monitoring
    public function queueEntryFeed()
    {
        $driverqueues = Driverqueue::all();

        return view('queueEntries.index',compact('driverqueues'));
    }

    //Queue from 24 hours from it's last tapped from queue rfid
    public function queueFeed()
    {
        $driverqueues = Driverqueue::all();

        return view('queueEntries.queue',compact('driverqueues'));
    }


}
