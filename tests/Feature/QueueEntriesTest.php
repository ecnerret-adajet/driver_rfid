<?php

namespace Tests\Feature;

use DB;
use App\Log;
use App\Truck;
use Carbon\Carbon;
use App\QueueEntry;
use Tests\TestCase;
use App\Driverqueue;
use App\Traits\QueueTrait;
use League\Fractal\Manager;
use App\Traits\NotDriverTrait;
use Illuminate\Support\Facades\Session;
use League\Fractal\Resource\Collection;
use App\Transformers\QueueEntriesTransformer;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QueueEntriesTest extends TestCase
{

    use NotDriverTrait, QueueTrait;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testQueueCount()
    {
        // $this->assertTrue(true);

         $queues = QueueEntry::with('truck','truck.plants:plant_name','truck.capacity','qshipment')
                            ->whereDate('created_at',Carbon::today()->subDays(2))
                            ->where('driverqueue_id',1)
                            // ->whereNotIn('CardholderID',$checkTruckscaleOut->values()->all())
                            ->whereNotNull('driver_availability')
                            ->whereNotNull('truck_availability')
                            ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                            ->whereNotNull('isTappedGateFirst')
                            ->orderBy('LocalTime','ASC')
                            ->get()
                            ->unique('CardholderID');

        echo json_encode($queues->values()->all(), JSON_PRETTY_PRINT);
    }

    /**
     *
     * Test function for newly applied 24 hours from driver's tap to expire
     *
     */
    public function testQueueWithinDay()
    {
        $queues = QueueEntry::whereDate('created_at', '>=', Carbon::now()->subHours(24))
                            ->where('driverqueue_id',1)
                            // ->whereNotIn('CardholderID',$checkTruckscaleOut->values()->all())
                            ->whereNotNull('driver_availability')
                            ->whereNotNull('truck_availability')
                            ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                            ->whereNotNull('isTappedGateFirst')
                            ->orderBy('LocalTime','DESC')
                            ->with('truck','truck.plants:plant_name','truck.capacity','shipment')
                            ->get()
                            ->unique('CardholderID');

        $this->get($queues)->getContent();

        echo json_encode($queues->values()->all(), JSON_PRETTY_PRINT);
    }

    /**
     * Test Function for expired queueu after 24 hours
     */
    public function testExpiredQueues()
    {

        $expiredDate = Carbon::today()->subDay(1);
        $subDay = Carbon::now()->subHours(24);

        $queues = QueueEntry::whereDate('created_at', '<=', $subDay)
                            ->where('driverqueue_id',1)
                            // ->whereNotIn('CardholderID',$checkTruckscaleOut->values()->all())
                            ->doesntHave('shipment')
                            ->whereNotNull('driver_availability')
                            ->whereNotNull('truck_availability')
                            ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                            ->whereNotNull('isTappedGateFirst')
                            ->orderBy('LocalTime','DESC')
                            ->with('truck','truck.plants:plant_name','truck.capacity','shipment')
                            ->get()
                            ->unique('CardholderID');

        $this->get($queues)->getContent();

        echo json_encode($queues->values()->all(), JSON_PRETTY_PRINT);
        // echo json_encode($queues->count(), JSON_PRETTY_PRINT);
    }

    public function testQueueEntriesOlder()
    {

        $last_entry = Carbon::parse($this->testSearchQueueEntries());

        //  Session::put('queueDate', Carbon::now()->subHours(24));
        // Session::put('queueDate', Carbon::now()->subDays(2)->subHours(24));
       $latestDate = Carbon::now()->subDay();
        $olderDate = Carbon::now()->subDays(2);

        $queues = QueueEntry::where('LocalTime', '>=' ,$olderDate)
                            ->where('LocalTime', '<' ,$last_entry)
                            ->where('driverqueue_id',1)
                            // ->whereNotIn('CardholderID',$checkTruckscaleOut->values()->all())
                            ->doesntHave('shipment')
                            ->whereNotNull('driver_availability')
                            ->whereNotNull('truck_availability')
                            ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                            ->whereNotNull('isTappedGateFirst')
                            ->orderBy('LocalTime','DESC')
                            ->with('truck','truck.plants:plant_name','truck.capacity','shipment')
                            ->get()
                            ->unique('CardholderID')
                            ->values()->all();

        echo json_encode($queues, JSON_PRETTY_PRINT);
    }

    public function testSearchQueueEntries()
    {

        // Session::put('queueDate', $search_date);
        Session::put('queueDate', Carbon::now()->subDay());
        $dateSearch = Session::get('queueDate');

        // $checkTruckscaleOut = collect(Log::truckscaleOutFromQueue($driverqueue_id))->unique();

        $queues = QueueEntry::with('truck','truck.plants:plant_name','truck.capacity','qshipment')
                            ->whereDate('LocalTime',  '>=', $dateSearch) // get less than 24 hours from tap
                            ->where('driverqueue_id',1)
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
        $final = end($manager->createData($resource)->toArray()['data'])['LocalTime'];

        echo json_encode($final, JSON_PRETTY_PRINT);
    }

    public function testStoreQueueEntries()
    {

        $driverLocation = Driverqueue::where('id',1)->first();

        $lastLogEntry = Log::where('DoorID',$driverLocation->door)
                        ->where('ControllerID', $driverLocation->controller)
                        ->whereNotIn('CardholderID',$this->notDriver())
                        ->where('CardholderID', '>=', 15)
                        ->orderBy('LocalTime','DESC')
                        ->with('driver','driver.image','driver.truck','driver.hauler')
                        ->first();


        echo json_encode($lastLogEntry, JSON_PRETTY_PRINT);
    }


}
