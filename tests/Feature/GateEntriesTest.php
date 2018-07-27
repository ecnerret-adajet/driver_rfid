<?php

namespace Tests\Feature;

use App\GateEntry;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GateEntriesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * Test driver entries from main gate RFID
     *
     * @return void
     */
    public function testGateEntriesLogs()
    {
        $gateEntries = GateEntry::where('driverqueue_id',1)
                                ->whereDate('LocalTime',Carbon::today()->subDays(2))
                                ->orderBy('id','ASC')
                                ->get()
                                ->unique('CardholderID')
                                ->values()
                                ->all();

        echo json_encode($gateEntries,JSON_PRETTY_PRINT);
    }
}
