<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driverversion extends Model
{
    protected $fiillable = [
        'plate_number',
        'vendor',
        'card_assigned',
        'start_date',
        'end_date',
    ];
    
    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function getDates()
    {
        return [];
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }


}
