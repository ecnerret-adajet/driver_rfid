<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;
use App\Driverqueue;
use Carbon\Carbon;
use App\Shipment;
use App\QueueEntry;
use App\Log;
use DB;

class UpdateShipment extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'command:UpdateShipment';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Update shipment logs from driver RFID';

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

        Session::put('queueDate', Carbon::now()->subDay());
        $dateSearch = Session::get('queueDate');

        $driverqueues = Driverqueue::pluck('id');

        $queues = QueueEntry::whereIn('driverqueue_id',$driverqueues)
        ->whereDate('LocalTime', '>=', $dateSearch)
        ->doesntHave('shipment')
        ->whereNotNull('driver_availability')
        ->whereNotNull('truck_availability')
        ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
        ->whereNotNull('isTappedGateFirst')
        ->orderBy('LocalTime','DESC')
        ->get()
        ->unique('CardholderID')
        ->values()->all();

        $queueObject = array();

        foreach($queues as $key => $log)  {
            $amp = '&';
            $data = array(
                'LogID' => $log->LogID.$amp,
            );
            array_push($queueObject, $data);
        }

        $collection = collect($queueObject);

        $LogID =  'LogID='.$collection->implode('LogID', 'LogID=');
        // Post to new API
        $response = Curl::to('http://10.96.4.39/sapservice/api/assignedshipment2')
        ->withContentType('application/x-www-form-urlencoded')
        ->withData( $LogID )
        ->post();
    }
}
