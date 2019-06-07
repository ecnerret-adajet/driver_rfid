<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Replacement;

class ReplacementTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Replacement $replacement)
    {
        return [
            'id' => $replacement->id,
            'user' => $replacement->user,
            'card' => $replacement->card,
            'cardholder' => $replacement->driver->cardholder,
            'driver' => $replacement->driver,
            'driver-image' => $replacement->driver->image,
            'truck' => $replacement->driver->truck,
            'hauler' => $replacement->driver->hauler,
            'reason_replacement' => $replacement->reason_replacement,
            'remarks' => $replacement->remarks,
            'status' => $replacement->status,
            'marked_by' => $replacement->markedby,
            'created_at' => (string) $replacement->created_at
        ];
    }
}
