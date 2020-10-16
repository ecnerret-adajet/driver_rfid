<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\ChangeOrigin;

class ChangeOriginTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(ChangeOrigin $changeOrigin)
    {
        return [
            'id' => $changeOrigin->id,
            'driver_name' => $changeOrigin->driver_name,
            'remarks' => $changeOrigin->remarks,
            'is_approved' => $changeOrigin->is_approved,
            'truck' => $changeOrigin->truck,
            'hauler' => $changeOrigin->truck ? $changeOrigin->truck->hauler : '',
            'plant' => $changeOrigin->plant,
            'approval_remarks' => $changeOrigin->approval_remarks,
            'approvalType' => $changeOrigin->approval_type_id != 0 ? $changeOrigin->ApprovalType : 0,
            'created_at' => (string) $changeOrigin->created_at
        ];
    }
}
