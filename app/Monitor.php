<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    protected $connection = "sqlsrv";
    protected $fillable = [
    	'remarks',
        'odometer',
        'ship_date'
    ];

    protected $dates = [
        'ship_date'
    ];

    public function user(){
    	return $this->belongsTo('App\User');
    }

    public function driver()
    {
        return $this->belongsTo('App\Driver');
    }

    public function Log()
    {
        return $this->belongsTo('App\Log','log_ID','LogID');
    }

    public function location()
    {
    	return $this->belongsTo('App\Location');
    }

    public function status(){
    	return $this->belongsTo('App\Status');
    }

    public function duration(){
    	return $this->belongsTo('App\Duration');
    }

    public function detail(){
    	return $this->belongsTo('App\Detail');
    }
}
