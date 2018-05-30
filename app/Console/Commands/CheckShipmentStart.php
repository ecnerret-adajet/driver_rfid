<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;
use App\Driverqueue;
use Carbon\Carbon;
use App\Shipment;
use App\Log;
use DB;


class CheckShipmentStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CheckShipmentStart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the shipments table when a driver leave the plant';

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
        // get all queue entries within the day in all location
        $driverqueues = Driverqueue::all();

        foreach ($driverqueues as $driverqueue) {
            $check_truckscale_out = Log::truckscaleOutLocationObject($driverqueue->ts_out_controller, null);
            $log_lineups = $check_truckscale_out->unique('CardholderID');
            $queueObject = array();

            foreach($log_lineups as $key => $log)  {
                    foreach($log->drivers as $x => $driver) {
                        $amp = '&';
                        $data = array(
                            'LogID' => $log->LogID.$amp,
                        );
                        array_push($queueObject, $data);
                    }
            }             

        }

        $collection = collect($queueObject);
        $LogID =  'LogID='.$collection->implode('LogID', 'LogID=');
        $response = Curl::to('http://10.96.4.39/sapservice/api/assignedshipment')
        ->withContentType('application/x-www-form-urlencoded')
        ->withData( $LogID )
        ->post();
    }
}
