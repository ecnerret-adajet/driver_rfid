<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\AccessGroup;
use App\Truck;
use App\Card;
use App\Cardholder;
use App\Driverqueue;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        // $response = $this->get('/');

        // $response->assertStatus(200);
        
        $this->assertTrue(true);
    }

    /**
     * Test AccessGroups
     */
    public function testAccessGroups()
    {
        //  $access_groups = ['1' => 'All location'] + AccessGroup::where('AccessGroupName', 'LIKE', '%TM%')->pluck('AccessGroupName','AccessGroupID')->all();
         $access_groups = ['0' => 'All location'] + Driverqueue::pluck('title','id')->all();

         echo json_encode($access_groups, JSON_PRETTY_PRINT);
    }

    /**
     * Test Card ID from truck -> driver 
     */
    public function testCardholderTruck()
    {
        $truck = Truck::where('id','1709')->first();

        $card = Card::where('CardholderID',$truck->driver->cardholder_id)
                    ->where('AccessGroupID',1);
                    // ->update(['AccessGroupID' => '6']);

        // $card = Card::where('Cardholder')
        // $card = Card::where('')

        echo json_encode($truck, JSON_PRETTY_PRINT);
    }

}
