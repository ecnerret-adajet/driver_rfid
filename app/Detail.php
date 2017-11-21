<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    protected $connection = "sqlsrv";
    protected $fillable = [
    	'code',
    	'remarks'
    ];

    public function getDates()
    {
        return [];
    }

    public function monitors(){
    	return $this->hasMany('App\Monitor');
    }

    public function getFullDetailsAttribute()
    {
        return $this->code . ' - ' .$this->remarks;
    }
}
