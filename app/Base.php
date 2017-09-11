<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    protected $fillable = [
        'profit_center',
        'cost_center',
        'origin'
    ];

    public function truck()
    {
        return $this->hasOne('App\Truck');
    }
}
