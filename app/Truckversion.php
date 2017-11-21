<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Truckversion extends Model
{
    protected $fillable = [
        'cardholder_id',
        'card_id',
        'driver_name',
        'plate_number',
        'hauler'
    ];
    
    public function getDates()
    {
        return [];
    }

    
}
