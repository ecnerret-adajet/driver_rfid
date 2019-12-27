<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $connection = "sqlsrv_central";
    protected $table = "vehicles";
    public $timestamps = false;

    protected $dates = ['LocalTime'];

    public function gpsdevice(){
        return $this->belongsTo(GpsDevice::Class, 'gps_device_id');
    }

}
