<?php

use Illuminate\Database\Seeder;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            array(
                'name' => 'Approver',
                'display_name' => 'Approver',
                'description' => 'Approver'
            ),
        ]);
    }
}
