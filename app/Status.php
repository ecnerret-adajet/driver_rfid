<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $connection = "sqlsrv";
    protected $fillable = [
    	'code',
    	'status'
    ];

    public function getDates()
    {
        return [];
    }

    public function monitors(){
    	return $this->hasMany('App\Monitor');
    }

}
