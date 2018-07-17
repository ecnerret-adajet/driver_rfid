<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Transaction;
use App\Log;
use Carbon\Carbon;
use App\Truck;
use App\Shipment;

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

    // public function testCallLastTrip()
    // {
    //     $call = Truck::callLastTrip('MV 528655');
    //     $this->get($call)->getContent();
        
    //     echo json_encode($call, JSON_PRETTY_PRINT);
    // }

}
