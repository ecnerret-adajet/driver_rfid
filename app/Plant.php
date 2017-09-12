<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    public function truck()
    {
        return $this->hasOne('App\Truck');
    }
}
