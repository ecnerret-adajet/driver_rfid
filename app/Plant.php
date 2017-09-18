<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    public function truck()
    {
        return $this->hasOne('App\Truck');
    }

    //get truck associated from plant model
    public function trucks()
    {
        return $this->belongsToMany('App\Truck');
    }
}
