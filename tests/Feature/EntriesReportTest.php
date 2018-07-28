<?php

namespace Tests\Feature;

use App\Log;
use App\Truck;
use App\Shipment;
use App\GateEntry;
use Carbon\Carbon;
use Tests\TestCase;
use App\Transaction;
use League\Fractal\Manager;
use Illuminate\Support\Facades\Session;
use League\Fractal\Resource\Collection;
use App\Transformers\EntryReportTransformer;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EntriesReportTest extends TestCase
{
    /**
    * A basic test example.
    * @group terter
    *
    * @return void
    */

    // public function testExample()
    // {
        //     $getLastDR = Transaction::getLastDr('MV 348000','2018-06-13');
        //     $this->get($getLastDR)->getContent();

        //     echo json_encode($getLastDR, JSON_PRETTY_PRINT);
        // }

        /**
        * A basic test example.
        * @group testTruckGateIn
        *
        * @return void
        */
        public function testTruckGateIn()
        {
            // $gate = Log::truckGateIn('373','2018-06-18 00:26:31.000');
            // $this->get($gate)->getContent();

            // echo json_encode($gate, JSON_PRETTY_PRINT);

            $shipment = Shipment::getShipment('1592687');

            echo json_encode($shipment, JSON_PRETTY_PRINT);
        }

        /**
        * Test Last trip by plate number to DRFP database
        *
        * @return void
        */
        public function testCallLastTrip()
        {
            $call = Truck::callLastTrip('MV 528655');
            $this->get($call)->getContent();

            echo json_encode($call, JSON_PRETTY_PRINT);
        }

        /**
        * Test driver logs to display from page
        *
        * @return JSON
        */
        public function testDisplayEntries()
        {
            Session::put('date', Carbon::today()->subDay());
            $dateSearch = Session::get('date');

            // $entries = GateEntry::where('driverqueue_id',1)
            // ->whereBetween('LocalTime', [$dateSearch->format('Y-m-d 00:00:00'), $dateSearch->format('Y-m-d 23:59:00')])
            // ->get()
            // ->unique('CardholderID');

            $entries = GateEntry::with('queueEntry',
            'hasShipment',
            'hasShipment.loading',
            'hasTruckscaleIn',
            'hasTruckscaleOut',
            'hasGateOut')
            ->where('driverqueue_id',1)
            ->whereBetween('LocalTime', [$dateSearch->format('Y-m-d 00:00:00'), $dateSearch->format('Y-m-d 23:59:00')])
            ->get()
            ->unique('driver_name');

            $uniqueEntires = $entries->values()->all();

            $manager = new Manager();
            $resource = new Collection($uniqueEntires, new EntryReportTransformer());

            echo json_encode($manager->createData($resource)->toArray(), JSON_PRETTY_PRINT);
        }

    }
