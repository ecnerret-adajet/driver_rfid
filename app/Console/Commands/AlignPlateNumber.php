<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Truck;

class AlignPlateNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:align-plate-number';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Align Truck Monitoring plate number to centralize vehicle';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Truck $truck)
    {
        parent::__construct();
        $this->truck = $truck;
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

    /**
     * Update and format plate numbers
     *
     * @return void
     */
    public function formatTrucks()
    {
        $this->info('Formatting Plate Numbers...'."\n");
        $trucks = Truck::all();
        $counter = 0;

        $bar = $this->output->createProgressBar(count($trucks));

        $bar->start();

        foreach($trucks as $truck) {
                $mark1 = $this->processPlateNumber($truck->plate_number);

                $truck->plate_number = $mark1;

                if(!empty($truck->reg_number) || $truck->reg_number != "") {
                    $mark2 = $this->processPlateNumber($truck->reg_number);
                    $truck->reg_number = $mark2;
                }

                $truck->save();
                $truck->wasChanged() ? $counter++ : $counter;

                $bar->advance();
        }

        $bar->finish();

        if($counter == 0) {
            $this->info("\n\n".'All plate numbers is already formatted');
        } else {
            $this->info("\n\n".'Total formatted plate numbers: '.$counter);
        }
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
