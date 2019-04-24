<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;
use App\Cardholder;
use App\Pickup;
use App\Log;

class PickupTest extends TestCase
{
    /**
     * @test
     */
    public function can_return_unserved_pickups_with_null_cardholder_id()
    {
        $response = $this->actingAs($this->defaultUser(), 'api')->json('GET','api/pickups-unserved');

        $response->assertStatus(200)
            ->assertJsonMissing([
                'cardholder_id' => !null
            ])
            ->assertJsonStructure([
                'data' =>  [
                    '*' => [ 'id','plate_number','company','driver_name','remarks','deactivated_date','activation_date','do_number','created_at' ]
                ]
            ]);

    }

    /**
     * @test
     */
    public function can_return_served_pickups_where_availability_equals_to_false()
    {
        $response = $this->actingAs($this->defaultUser(), 'api')->json('GET','api/pickups-served');

        $response->assertStatus(200)
            ->assertJsonMissing([
                'availability' => "1"
            ])
            ->assertJsonStructure([
                'data' =>  [
                    '*' => [ 'id','plate_number','company','driver_name','remarks','deactivated_date','activation_date','availability','do_number','created_at' ]
                ]
            ]);

    }

    /**
     * @test
     */
    public function can_return_assigned_pickups_where_availability_equals_to_true()
    {
        $response = $this->actingAs($this->defaultUser(), 'api')->json('GET','api/pickups-assigned');

        $response->assertStatus(200)
            ->assertJsonMissing([
                'availability' => "0"
            ])
            ->assertJsonStructure([
                'data' =>  [
                    '*' => [ 'id','plate_number','company','driver_name','remarks','deactivated_date','activation_date','availability','do_number','created_at' ]
                ]
            ]);

    }
}
