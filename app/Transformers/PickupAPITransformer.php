<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Carbon\Carbon;
use App\Pickup;
use App\Log;

class PickupAPITransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Pickup $pickup)
    {

        $checkOutDate = date('m/d/y h:i:s A', strtotime($pickup->created_at))  != date('m/d/y h:i:s A', strtotime($pickup->updated_at)) ?
                        date('m/d/y h:i:s A', strtotime($pickup->updated_at)) :
                        'NO TIME OUT';

        $date = empty($pickup->activation_date) ? $pickup->created_at : $pickup->activation_date;
        $truckscaleIn = Log::getInEntry($pickup->cardholder_id, $date)->first();
        $truckscaleOut = Log::getOutEntry($pickup->cardholder_id, $date)->first();
        $timeDiff = !empty($truckscaleIn) ? $truckscaleIn['LocalTime']->diffInHours($truckscaleOut['LocalTime'])." Hour(s)" : '';

        return [
            'id' => $pickup->id,
            'cardholder_id' => $pickup->cardholder_id,
            'pickup_deploy_name' => !empty($pickup->cardholder->Name) ? $pickup->cardholder->Name : 'UNPROCESS',
            'availability' => $pickup->availability,
            'driver_name' => $pickup->driver_name,
            'plate_number' => $pickup->plate_number,
            'company' => $pickup->company,
            'do_number' => $pickup->do_number,
            'created_at' => (string) date('Y-m-d h:i A',strtotime($pickup->created_at)),
            'checkout_date' => $checkOutDate,
            'truckscale_in' => !empty($truckscaleIn['LocalTime']) ? (string) date('Y-m-d h:i A',strtotime($truckscaleIn['LocalTime'])) : 'N/A',
            'truckscale_out' => !empty($truckscaleOut['LocalTime']) ? (string) date('Y-m-d h:i A',strtotime($truckscaleOut['LocalTime'])) : 'N/A',
            'time_diff' => $timeDiff,
            'deactivated_date' => !empty($pickup->deactivated_date) ? (string) date('Y-m-d h:i A',strtotime($pickup->deactivated_date)) : 'no-deactivate-date',
            'activation_date' => !empty($pickup->activation_date) ? (string) date('Y-m-d h:i A',strtotime($pickup->activation_date)) : 'no-activation-date',

        ];
    }
}
