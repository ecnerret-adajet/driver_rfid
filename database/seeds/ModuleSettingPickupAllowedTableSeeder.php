<?php

use Illuminate\Database\Seeder;

class ModuleSettingPickupAllowedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('module_settings')->insert([
            array(
                'user_id' => 1,
                'modelable_array' => '[1,2,3,4,5,6,7,8]',
                'modelable_type' => 'App\Pickup'
            ),
        ]);
    }
}
