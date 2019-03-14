<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Log;
use App\Pickup;
use Carbon\Carbon;

class PickupAPITransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Pickup $pickup)
    {
        return [
            'id' => $pickup->id,
            'pickup_num' => $pickup->cardholder->name,
            'availability' => $pickup->availability,
            'driver_name' => $pickup->driver_name,
            'plate_number' => $pickup->plate_number,
            'company' => $pickup->company,
            'do_number' => $pickup->do_number,
            'created_at' => $pickup->created_at,
            'updated_at' => $pickup->updated_at,
            'cardholder_id' => $pickup->cardholdler_id,
            'activation_date' => $pickup->activation_date,

        ];
    }
}
