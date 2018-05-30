<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\GateEntry;
use App\Driverqueue;

class GateEntryEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $gateEntry;
    public $driverqueue;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(GateEntry $gateEntry, Driverqueue $driverqueue)
    {
        $this->gateEntry = $gateEntry;
        $this->driverqueue = $driverqueue;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('gate.'.$this->driverqueue->id);
    }

    /**
     * Choose data to sent to pusher
     */
    public function broadcastWith()
    {
        return [
            'gateEntry' => $this->gateEntry,
            'driverqueue' => $this->driverqueue->id
        ];
    }
}
