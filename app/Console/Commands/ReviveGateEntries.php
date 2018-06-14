<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\NotDriverTrait;
use App\Log;
use App\Driverqueue;
use Carbon\Carbon;
use App\Cardholder;
use App\GateEntry;
use App\Shipment;

class ReviveGateEntries extends Command
{
    use NotDriverTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ReviveGateEntries {driverqueue_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revive driver entries from main gate area, for defined location';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Log $log)
    {
        parent::__construct();

        $this->log = $log;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info('Reviving entries...');

        $driverqueue = Driverqueue::where('id',$this->argument('driverqueue_id'))->first();

        $lastLogEntry = $this->log->where('DoorID',$driverqueue->gate->door)
                        ->where('ControllerID', $driverqueue->gate->controller)
                        ->whereNotIn('CardholderID',$this->notDriver())
                        ->whereDate('LocalTime',Carbon::today())
                        ->where('CardholderID', '>=', 15)
                        ->orderBy('LocalTime','DESC')
                        ->with('driver','driver.image','driver.truck','driver.hauler')
                        ->get()
                        ->unique('CardholderID')
                        ->values()
                        ->all();

        foreach($lastLogEntry as $key => $entry) {
            $gateEntry = GateEntry::updateOrCreate(
                [
                    'LogID' => $entry->LogID,
                    'shipment_number' => Shipment::checkIfShipped($entry->CardholderID,null)->first(),
                    'isShipmentStarted' => 0,
                    'driver_availability' => !empty($entry->driver) && $entry->driver->availability == 1 ? 1 : null,
                    'truck_availability' =>  !empty($entry->driver->truck) && $entry->driver->truck->availability  == 1 ? 1 : null,
                ],
                [
                    'CardholderID' => $entry->CardholderID,
                    'gate_number' =>  $key + 1 ."-". $driverqueue->id,
                    'driver_name' => $entry->driver->name,
                    'avatar' =>   !empty($entry->driver->image->avatar) ? $entry->driver->image->avatar : $entry->driver->avatar,
                    'plate_number' => !empty($entry->driver->truck) ? $entry->driver->truck->plate_number : "NO PLATE NUMBER",
                    'hauler_name' => !empty($entry->driver->name) ? $entry->driver->hauler->name : "NO HAULER",
                    'driverqueue_id' => $driverqueue->id,
                    'LocalTime' => $entry->LocalTime,
                ]
            );
        }   
        
        $this->info('Revived '.count($lastLogEntry).' entries successfully!');
    }
}
