<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Duration extends Model
{
    protected $connection = "sqlsrv";
    protected $fillable = [
    	'days',
    	'remarks'
    ];

    public function getDates()
    {
        return [];
    }

    public function monitors()
    {
    	return $this->hasMany('App\Monitor');
    }

    public function getFullDurationsAttribute()
    {
        return $this->days. ' - ' .$this->remarks;
    }
}
