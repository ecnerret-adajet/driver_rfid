<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
         '\App\Console\Commands\UpdateShipment',
         '\App\Console\Commands\UpdateShipmentOlder',
         '\App\Console\Commands\CheckShipmentStart',
         '\App\Console\Commands\ReviveGateEntries',
         '\App\Console\Commands\ReviveQueueEntries',
         '\App\Console\Commands\PushGateToQueueSAP',
         '\App\Console\Commands\PlateNumberAlignment',
         '\App\Console\Commands\DoNumberStatusUpdate',
         '\App\Console\Commands\DoNumberServedStatus',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('command:UpdateShipment')->everyFiveMinutes();
         $schedule->command('command:CheckShipmentStart')->everyFiveMinutes();

         $schedule->command('command:do_number_status')->hourly();
         $schedule->command('command:do_number_status_server')->hourlyAt(30);
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
