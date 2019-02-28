<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\QueueEntry;
use Carbon\Carbon;

class LastDriverTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(QueueEntry $queueEntry)
    {
        return [
            'id' => $queueEntry->id,
            'avatar' => $queueEntry->avatar,
            'driver_name' => $queueEntry->driver_name,
            'plate_number' => $queueEntry->plate_number,
            'hauler_name' => $queueEntry->hauler_name,
            'LocalTime' => $queueEntry->LocalTime,
            'isDRCompleted' => $queueEntry->isDRCompleted,
            'driver_availability' => $queueEntry->driver_availability,
            'truck_availability' => $queueEntry->truck_availability,
            'isTappedGateFirst' => $queueEntry->isTappedGateFirst,
            'shipment_number' => $queueEntry->shipment_number,
        ];
    }
}
