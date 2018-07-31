<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\QueueEntry;
use Carbon\Carbon;

class QueueEntriesTransformer extends TransformerAbstract
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
            'queue_number' => $queueEntry->queue_number,
            'driver_name' => $queueEntry->driver_name,
            'avatar' => $queueEntry->avatar,
            'plate_number'=> $queueEntry->plate_number,
            'hauler_name' => $queueEntry->hauler_name,
            'driverqueue_id' => $queueEntry->driverqueue_id,
            'shipment_number' => $queueEntry->shipment_number,
            'LogID' => $queueEntry->LogID,
            'CardholderID' => $queueEntry->CardholderID,
            'LocalTime' => $queueEntry->LocalTime,
            'isDRCompleted' => $queueEntry->isDRCompleted,
            'driver_availability' => $queueEntry->driver_availability,
            'truck_availability' => $queueEntry->truck_availability,
            'isTappedGateFirst' => $queueEntry->isTappedGateFirst,
            'isSecondDelivery' => $queueEntry->isSecondDelivery,
            'created_at' => $queueEntry->created_at,
            'truck' => $queueEntry->truck,
            'shipment' =>  $queueEntry->withinDayShipment, //qshipment
            'lastCreated' => Carbon::parse($queueEntry->created_at)->diffForHumans(),
        ];
    }
}
