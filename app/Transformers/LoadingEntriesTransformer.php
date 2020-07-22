<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\LoadingEntry;
use Carbon\Carbon;

class LoadingEntriesTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(LoadingEntry $loadingEntry)
    {
        return [
            'id' => $loadingEntry->id,
            'queue_number' => $loadingEntry->queue_number,
            'driver_name' => $loadingEntry->driver_name,
            'avatar' => $loadingEntry->avatar,
            'plate_number' => $loadingEntry->plate_number,
            'hauler_name' => $loadingEntry->hauler_name,
            'driverqueue_id' => $loadingEntry->driverqueue_id,
            'shipment_number' => $loadingEntry->shipment_number,
            'LogID' => $loadingEntry->LogID,
            'CardholderID' => $loadingEntry->CardholderID,
            'LocalTime' => $loadingEntry->LocalTime,
            'isDRCompleted' => $loadingEntry->isDRCompleted,
            'driver_availability' => $loadingEntry->driver_availability,
            'truck_availability' => $loadingEntry->truck_availability,
            'isTappedGateFirst' => $loadingEntry->isTappedGateFirst,
            'isSecondDelivery' => $loadingEntry->isSecondDelivery,
            'created_at' => $loadingEntry->created_at,
            'truck' => $loadingEntry->truck,
            'shipment' =>  $loadingEntry->withinDayShipment, //qshipment
            'lastCreated' => Carbon::parse($loadingEntry->created_at)->diffForHumans(),
            // addional data
            'plant_out' => !empty($loadingEntry->hasGateOut) ? $loadingEntry->hasGateOut->LocalTime : '',
        ];
    }
}
