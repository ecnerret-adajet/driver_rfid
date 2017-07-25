<?php

use Illuminate\Database\Seeder;

class ClasificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('sqlsrv_four')->table('clasifications')->insert([
            array('name' => 'Transfer'),
            array('name' => 'Lost ID'),
        ]);
    }
}
