<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $unguard = [
        '*'
    ];

    public function getDates()
    {
        return [];
    }

}
