<?php

use Illuminate\Database\Seeder;

class HaulerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('haulers')->insert([
            array('name' => 'AFS'),
            array('name' => 'CLS'),
            array('name' => 'CWLT'),
            array('name' => 'DOUBLE AC'),
            array('name' => 'EWG'),
            array('name' => 'JSTY'),
            array('name' => 'JUNROSS'),
            array('name' => 'KAIZEN'),
            array('name' => 'CKRV'),
            array('name' => 'LDL'),
            array('name' => 'MEJECK'),
            array('name' => 'MENRUZ'),
            array('name' => 'MZL'),
            array('name' => 'QUIRIJERO'),
            array('name' => 'RAC'),
            array('name' => 'RJC'),
            array('name' => 'SIMGUAN'),
            array('name' => 'ST. VINCENT'),
            array('name' => 'TITAN'),
            array('name' => 'WHITE RIM'),
            array('name' => 'ZMB'),
            array('name' => 'MSCR TRUCKING'),
            array('name' => 'GLI88'),
            array('name' => 'MABOLO TRUCKING'),
            array('name' => 'UNOVO'),
            array('name' => 'JESONE'),
            array('name' => 'PPC'),
            array('name' => 'FVR'),
            array('name' => 'MANG FONSO'),
            array('name' => 'BOATMAN'),
            array('name' => 'JUNECO'),
            array('name' => 'KLFM'),
            array('name' => 'TRACTOR HUB'),
            array('name' => 'DOYEN F1')
        ]);
    }
}
