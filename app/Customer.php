<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $connection = "sqlsrv";

    public function getDates()
    {
        return [];
    }
    
    public function Log()
    {
        return $this->belongsTo('App\Log','log_ID','LogID');
    }
}
