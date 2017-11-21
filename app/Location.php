<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $connection = "sqlsrv";
    protected $fillable = [
    		'code',
    		'region',
    		'designation'
    ];

    public function getDates()
    {
        return [];
    }

    public function monitors(){
    	return $this->hasMany('App\Monitor');
    }}
