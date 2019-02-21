<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\QueueEntry;
use Carbon\Carbon;

class QueueEntriesDashTransformer extends TransformerAbstract
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
            'LogID' => $queueEntry->LogID,
            'CardholderID' => $queueEntry->CardholderID,
            'queue_number' => $queueEntry->queue_number,
            'driver_name' => $queueEntry->driver_name,
            'avatar' => $queueEntry->avatar,
            'plate_number'=> $queueEntry->plate_number,
            'driverqueue_id' => $queueEntry->driverqueue_id,
            'hauler_name' => $queueEntry->hauler_name,
            'truck' => $queueEntry->truck,
            'LocalTime' => $queueEntry->LocalTime,
            'shipment' => $queueEntry->withinDayShipment,
            'isDRCompleted' => $queueEntry->isDRCompleted,
        ];
    }
}
