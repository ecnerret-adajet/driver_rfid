<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeOrigin extends Model
{
    protected $connection = "sqlsrv";

    protected $fillable = [
        'remarks',
        'driver_name',
        'approval_type_id',
        'approval_remarks'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function ApprovalType()
    {
        return $this->belongsTo(ApprovalType::class);
    }
}
