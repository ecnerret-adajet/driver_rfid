<?php

use Illuminate\Database\Seeder;

class CapacityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        DB::table('capacities')->insert([
            array(
                'description' => 'Truck 4W',
                'capacity' => '4'
            ),
            array(
                'description' => 'L300',
                'capacity' => '4'
            ),
            array(
                'description' => 'Multicab',
                'capacity' => '4'
            ),
            array(
                'description' => 'Truck 6W',
                'capacity' => '6'
            ),
            array(
                'description' => 'Truck 6W F',
                'capacity' => '6'
            ),
            array(
                'description' => '6W CV-Elf',
                'capacity' => '6'
            ),
            array(
                'description' => 'Truck 8W',
                'capacity' => '8'
            ),
            array(
                'description' => 'Truck 10W',
                'capacity' => '10'
            ),
            array(
                'description' => 'Truck 12W',
                'capacity' => '12'
            ),
            array(
                'description' => 'Truck 14W',
                'capacity' => '14'
            ),
            array(
                'description' => 'Bulk',
                'capacity' => '16'
            ),
            array(
                'description' => '2 axle 20ftr',
                'capacity' => '20'
            ),
          
        ]);
    }
}
