<?php

use Illuminate\Database\Seeder;

class ServerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('servers')->insert([
            array('name' => 'PFMC'),
            array('name' => 'LFUG'),
        ]);
    }
}
