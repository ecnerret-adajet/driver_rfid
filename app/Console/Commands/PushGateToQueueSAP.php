<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Log;
use App\Shipment;
use Carbon\Carbon;
use App\GateEntry;
use App\Driverqueue;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Session;


class PushGateToQueueSAP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:gatePushToSAP {driverqueue_id} {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push gate entries to queue entries';

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
        $this->info('Initiating...');

        $queues = GateEntry::where('driverqueue_id',$this->argument('driverqueue_id'))
            ->whereDate('LocalTime', $this->argument('date'))
            ->orderBy('LocalTime','DESC')
            ->get()
            ->unique('CardholderID')
            ->values()->all();

        $queueObject = array();

        foreach($queues as $log)  {
            // Post to new API
            $response = Curl::to('http://10.96.4.39/EmergencyService/api/entries/push')
            ->withContentType('application/x-www-form-urlencoded')
            ->withData( array( 'LogID' => $log->LogID ) )
            ->post();

            $this->info($response);

        }
        

         
    }
}
