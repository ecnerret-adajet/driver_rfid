<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BookRequestTest extends TestCase
{
    /**
     * @test
     */
    public function view_all_booking_requests()
    {
        $response = $this->actingAs($this->defaultUser(),'api')
                        ->json('GET','/api/booking-requests');

        \Log::info($response->getContent());

        $response->assertStatus(200);


        echo json_encode($response, JSON_PRETTY_PRINT);
    }
}
