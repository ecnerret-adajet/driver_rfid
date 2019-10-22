<?php

namespace App\Console\Commands;

use App\Gate;
use Illuminate\Console\Command;
use App\Truck;
use App\QueueEntry;
use App\GateEntry;
use Session;
use Carbon\Carbon;

class PlateNumberAlignment extends Command
{
    /**
     * The name and signature of the console command.
     * 
     * // model parameter = determine which model the alignment will perform
     * 
     * model choices [truck, gate, entry]
     * 
     * plant id = [1 = manila, 2 = lapaz, 3 = bataan]
     *
     * @var string
     */
    protected $signature = 'command:align-plate-number {model} {plant_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Align the truck plate number to official format';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Truck $truck, QueueEntry $queueEntry, GateEntry $gateEntry)
    {
        parent::__construct();
        $this->truck = $truck;
        $this->queueEntry = $queueEntry;
        $this->gateEntry = $gateEntry;
    }

    /**
     * Manipulate / Format plate number from truck monitoring
     *
     * @param string $value
     * @return void
     */
    public function processPlateNumber($value)
    {
        // rule1: check if has no space in value
        if(!strpos(trim($value), ' ')) {
            // rule2: if rule1 is true. then, check if first three character is a string
            // convert to array and loop to check if how many string is in the value param
            $convertedArray = str_split($value);
            $countString = 0;
            // detected character in a string
            foreach($convertedArray as $char) {
               if(is_numeric($char)) {
                    break;
               }
               $countString++;
            }
            // rule3: if rule2 is true. then, place space in every ending of the
            // find the last string in a array and insert a space
            array_splice($convertedArray, $countString, 0, " ");
            $filterString = implode($convertedArray);
            // Make sure it has only one space
            return preg_replace('/\s+/',' ', trim($filterString));

        } else {

            $mark1 = str_replace('-',' ',strtoupper($value));
            $mark2 = str_replace('MV', 'MV ', $mark1);
            return preg_replace('/\s+/',' ', trim($mark2));

        }

    }


    public function formatModel() 
    {
        Session::put('queueDate', Carbon::now()->subDay());
        $dateSearch = Session::get('queueDate');

        switch($this->argument('model')) {

            case "Truck":
                return Truck::all();
                break;

            case "QueueEntry":

                $queues = QueueEntry::with('truck','truck.plants:plant_name','truck.capacity','qshipment')
                    ->where('LocalTime',  '>=', $dateSearch)
                    ->where('driverqueue_id',$this->argument('plant_id'))
                    ->whereNotNull('driver_availability')
                    ->whereNotNull('truck_availability')
                    ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                    ->whereNotNull('isTappedGateFirst')
                    ->orderBy('LocalTime','ASC')
                    ->get()
                    ->unique('CardholderID')
                    ->values()->all();

                return $queues;
                break;

            case "GateEntry":
                $gateEntries = GateEntry::where('driverqueue_id',$this->argument('plant_id'))
                            ->whereDate('LocalTime',$dateSearch)
                            ->orderBy('id','ASC')
                            ->get()
                            ->unique('CardholderID')
                            ->values()
                            ->all();

                return $gateEntries;
                break;

            default: 
                $this->info("\n\n".'* Model option is not defined');
                return [];
        }

    }

    /**
     * Update and format plate numbers
     *
     * @return void
     */
    public function formatTrucks()
    {

        $this->info("\n".'Command initializing...');

        if(count($this->formatModel()) == 0) {
            return $this->info('* No entries found. Command will now terminate'."\n\n");
        }

        $counter = 0;

        $bar = $this->output->createProgressBar(count($this->formatModel()));

        $bar->start();

        foreach($this->formatModel() as $item) {

                $mark1 = $this->processPlateNumber($item->plate_number);

                $item->plate_number = $mark1;

                if (property_exists('plate_number', $item)) {
                    if(!empty($item->reg_number) || $item->reg_number != "") {
                        $mark2 = $this->processPlateNumber($item->reg_number);
                        $item->reg_number = $mark2;
                    }
                }                

                if ($item->isDirty()) {
                    $item->save();
                    $counter++;
                }

                $bar->advance();
        }

        $bar->finish();

        if($counter == 0) {
            return $this->info("\n\n".'All plate numbers is already formatted');
        }
            return $this->info("\n\n".'Total formatted plate numbers: '.$counter);

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->formatTrucks();
    }
}
