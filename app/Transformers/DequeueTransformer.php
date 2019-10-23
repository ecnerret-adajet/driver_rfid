<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Dequeue;

class DequeueTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Dequeue $dequeue)
    {
        return [
            'id' => $dequeue->id,
            'user_id' => $dequeue->user_id,
            'queue_entry_id' => $dequeue->queue_entry_id,
            'remarks' => $dequeue->remarks,
            'confirmed_by' => $dequeue->confirmed_by,
            'isApproved' => $dequeue->isApproved
        ];
    }
}
