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
        DB::table('clasifications')->insert([
            array('name' => 'Transfer'),
            array('name' => 'Lost ID'),
        ]);
    }
}
