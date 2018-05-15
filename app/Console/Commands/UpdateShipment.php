<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;
use App\Driverqueue;
use Carbon\Carbon;
use App\Shipment;
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

        // get all queue entries within the day in all location
        $driverqueues = Driverqueue::all();
        
        foreach($driverqueues as $driverqueue) {
            
            $check_truckscale_out = Log::truckscaleOutLocation($driverqueue->ts_out_controller);
            $gateEntries =  Log::barrierLocation($driverqueue->gate->door,$driverqueue->gate->controller);
            $result_lineups = Log::queueLocation($driverqueue->door, $driverqueue->controller, $check_truckscale_out, $gateEntries, Carbon::today());
            $log_lineups = $result_lineups->unique('CardholderID');
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

           $collection = collect($queueObject);
            $LogID =  'LogID='.$collection->implode('LogID', 'LogID=');
            $response = Curl::to('http://10.96.4.39/sapservice/api/assignedshipment')
            ->withContentType('application/x-www-form-urlencoded')
            ->withData( $LogID )
            ->post();


        }
    }
}
