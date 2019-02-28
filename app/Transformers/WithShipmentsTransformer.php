<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Shipment;
use Carbon\Carbon;

class WithShipmentsTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Shipment $shipment)
    {
        return [
            'id' => $shipment->id,
            'LogID' => $shipment->LogID,
            'shipment_number' => $shipment->shipment_number,
            'driver' => $shipment->driver,
            'created_at' => $shipment->created_at,
            'isTappedGateFirst' => $shipment->queueEntry->isTappedGateFirst,
            'isDRCompleted' => $shipment->queueEntry->isDRCompleted,
        ];
    }
}
