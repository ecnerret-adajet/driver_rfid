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

    public function monitors(){
    	return $this->hasMany('App\Monitor');
    }}
