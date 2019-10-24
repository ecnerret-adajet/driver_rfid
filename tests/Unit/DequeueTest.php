<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Faker\Factory;

class DequeueTest extends TestCase
{

    /**
     * @test
     */
    public function see_all_dequeues()
    {
        $response = $this->actingAs($this->defaultUser(),'api')
                        ->json('GET','api/dequeues');

         \Log::info($response->getContent());

        $response->assertStatus(200);

        echo "\n\n".json_encode($response, JSON_PRETTY_PRINT);

    }


}
