<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReplacementTest extends TestCase
{
    /**
     * @test
     */
    public function can_add_replacement()
    {
         $user = $this->defaultUser();
         $response = $this->actingAs($user, 'api')->json('POST', 'api/replacements', [
            'user_id' => $user_id = 1,
            'driver_id' => $driver_id = 1,
            'card_id' => $card_id = 2444,
            'reason_replacement' => $reason_replacement = 'Test Reason Replacement',
            'remarks' => $remarks = 'Test Remarks',
        ]);

        // \Log::info($response->getContent());

        $response->assertJsonStructure([
            'id',
            'user',
            'card',
            'driver',
            'reason_replacement',
            'remarks',
            'status'
        ])
        ->assertJson([
            'user_id' => $user_id,
            'driver_id' => $driver_id,
            'card_id' => $card_id,
            'reason_replacement' => $reason_replacement,
            'remarks' => $remarks
        ])
        ->assertStatus(200);

        $this->assertDatabaseHas('replacements',[
            'user_id' => $user_id,
            'driver_id' => $driver_id,
            'card_id' => $card_id,
            'reason_replacement' => $reason_replacement,
            'remarks' => $remarks
        ]);
    }

     /**
     * @test
     */
    public function can_return_replacements()
    {
        $response = $this->actingAs($this->defaultUser(), 'api')->json('GET',"api/replacements");

        \Log::info($response->getContent());

        $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'user',
                    'card',
                    'driver',
                    'reason_replacement',
                    'remarks',
                    'status'
                ]
            ]
        ]);
    }
}
