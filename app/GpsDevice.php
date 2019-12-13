<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GpsDevice extends Model
{
    protected $connection = "sqlsrv_central";
    protected $table = "gps_devices";
    public $timestamps = false;
}
