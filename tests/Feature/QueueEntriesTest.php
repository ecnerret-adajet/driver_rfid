<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\QueueEntry;
use Carbon\Carbon;
use DB;
use App\Truck;

class QueueEntriesTest extends TestCase
{
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


}
