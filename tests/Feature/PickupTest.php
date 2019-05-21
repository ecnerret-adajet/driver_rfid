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
     * pickup unserved
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
                    '*' => [
                        'id',
                        'pickup_deploy_name',
                        'availability',
                        'driver_name',
                        'company',
                        'do_number',
                        'created_at',
                        'checkout_date',
                        'truckscale_in',
                        'truckscale_out',
                        'time_diff',
                        'deactivated_date'
                    ]
                ]
            ]);

    }

    /**
     * @test
     * pickup served
     */
    public function can_return_served_pickups_where_availability_equals_to_false()
    {
        $response = $this->actingAs($this->defaultUser(), 'api')->json('GET','api/pickups-served');

        $response->assertStatus(200)
            ->assertJsonMissing([
                'deactivated_date' => null,
                'cardholder_id' => null,
            ])
            ->assertJsonStructure([
                'data' =>  [
                    '*' => [
                        'id',
                        'pickup_deploy_name',
                        'availability',
                        'driver_name',
                        'company',
                        'do_number',
                        'created_at',
                        'checkout_date',
                        'truckscale_in',
                        'truckscale_out',
                        'time_diff',
                        'deactivated_date'
                    ]
                ]
            ]);

    }

    /**
     * @test
     *  pickup assigned
     */
    public function can_return_assigned_pickups_where_availability_equals_to_true()
    {
        $response = $this->actingAs($this->defaultUser(), 'api')->json('GET','api/pickups-assigned');

        // \Log::info($response->getContent());

        $response->assertStatus(200)
            ->assertJsonMissing([
                'cardholder_id' => null,
                'deactivated_date' => !null
            ])
            ->assertJsonStructure([
                'data' =>  [
                    '*' => [
                        'id',
                        'pickup_deploy_name',
                        'availability',
                        'driver_name',
                        'company',
                        'do_number',
                        'created_at',
                        'checkout_date',
                        'truckscale_in',
                        'truckscale_out',
                        'time_diff',
                        'deactivated_date'
                    ]
                ]
            ]);

    }
}
