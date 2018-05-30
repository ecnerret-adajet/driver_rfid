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
use App\QueueEntry;
use App\Driverqueue;

class QueueEntryEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $queueEntry;
    public $driverqueue;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(QueueEntry $queueEntry, Driverqueue $driverqueue)
    {
        $this->queueEntry = $queueEntry;
        $this->driverqueue = $driverqueue;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('queue.'.$this->driverqueue->id);
    }

    /**
     * Choose data to sent to pusher
     */
    public function broadcastWith()
    {
        return [
            'queueEntry' => $this->queueEntry,
            'driverqueue' => $this->driverqueue->id
        ];
    }

    /**
     * Determine if this event should broadcast.
     *
     * @return bool
     */
    public function broadcastWhen()
    {
        return $this->queueEntry->driver_availability == null || 
               $this->queueEntry->truck_availability == null ||
               $this->queueEntry->shipment_number == null ||
               $this->queueEntry->isDRCompleted != "0000-00-00" ||
               $this->queueEntry->isTappedGateFirst == null;
    }
    
}
