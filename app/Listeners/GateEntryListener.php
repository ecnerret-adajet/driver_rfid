<?php

namespace App\Listeners;

use App\Events\GateEntryEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GateEntryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  GateEntryEvent  $event
     * @return void
     */
    public function handle(GateEntryEvent $event)
    {
        //
    }
}
