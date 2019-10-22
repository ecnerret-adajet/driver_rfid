<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\NotDriverTrait;
use App\Traits\QueueTrait;
use App\Log;
use App\Driverqueue;
use Carbon\Carbon;
use App\Cardholder;
use App\QueueEntry;
use App\GateEntry;
use App\Shipment;
use App\Truck;

class ReviveQueueEntries extends Command
{

    use NotDriverTrait, QueueTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ReviveQueueEntries {driverqueue_id} {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revive driver queue entries from queue RFID, for defined location format date 2018-06-19 00:00:00.000000';

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
       $this->info('Reviving queue entries...');

       $driverqueue = Driverqueue::where('id',$this->argument('driverqueue_id'))->first();

       $lastLogEntry = $this->log->where('DoorID',$driverqueue->door)
                        ->where('ControllerID', $driverqueue->controller)
                        ->whereNotIn('CardholderID',$this->notDriver())
                        ->whereDate('LocalTime',Carbon::parse($this->argument('date'))) // Carbon::today()
                        ->where('CardholderID', '>=', 15)
                        ->orderBy('LocalTime','ASC')
                        ->with('driver','driver.image','driver.truck','driver.hauler')
                        ->get()
                        ->unique('CardholderID')
                        ->values()
                        ->all();

        foreach($lastLogEntry as $key => $entry) {
            $queueEntry = QueueEntry::firstOrCreate(
                [
                    'LogID' => $entry->LogID,
                    
                ],
                [
                    'shipment_number' => Shipment::checkIfShipped($entry->CardholderID,null)->first(),
                    'driver_name' => $entry->driver->name,
                    'plate_number' => !empty($entry->driver->truck) ? $entry->driver->truck->plate_number : null,
                    'hauler_name' => !empty($entry->driver->hauler) ? $entry->driver->hauler->name : null,
                    'CardholderID' => $entry->CardholderID,
                    'queue_number' => $this->checkIfExist($driverqueue->id),
                    'isDRCompleted' =>  !empty($entry->driver->truck) ? Truck::callLastTrip($entry->plate_number) : null,
                    'isTappedGateFirst' => !empty(GateEntry::checkIfTappedFromGate($entry->CardholderID)) ? 1 : null,
                    'isSecondDelivery' => $this->checkIfReturned($entry->CardholderID) > 0 ? 1 : 0,
                    'driver_availability' => !empty($entry->driver) && $entry->driver->availability == 1 ? 1 : null,
                    'truck_availability' => !empty($entry->driver->truck) && $entry->driver->truck->availability == 1 ? 1 : null,
                    'isShipmentStarted' => 0,
                    'truck_id' => !empty($entry->driver->truck) ? $entry->driver->truck->id : null,
                    'avatar' => !empty($entry->driver->image) ? $entry->driver->image->avatar : $entry->driver->avatar,
                    'driverqueue_id' => $driverqueue->id,
                    'LocalTime' => $entry->LocalTime,
                ]
            );
        }   
        
        $this->info('Revived '.count($lastLogEntry).' entries queue successfully!');    
    }
}
