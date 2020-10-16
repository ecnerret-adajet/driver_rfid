<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ChangeOriginTest extends TestCase
{

    /**
     * @test
     */
    public function change_origin_lists()
    {
        $response = $this->actingAs($this->defaultUser(),'api')
                        ->json('GET','api/change-origins');

        $response->assertStatus(200);

        echo json_encode($response, JSON_PRETTY_PRINT);
    }
}
