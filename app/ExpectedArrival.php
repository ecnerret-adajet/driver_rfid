<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpectedArrival extends Model
{
    protected $fillable = [
        'expected_arrival',
        'remarks',
        'hauler_name',
        'plate_number',
        'arrival_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function hauler()
    {
        return $this->belongsTo(Hauler::class);
    }

}
