<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $connection = "sqlsrv_two";
    protected $table = "Card";
    public $timestamps = false;

    public function cardholder()
    {
    	return $this->belongsTo(Cardholder::class,'CardholderID','CardholderID');
    }

    public function binders() 
    {
        return $this->hasMany('App\Binder','card_id','CardID');
    }

    public function driver()
    {
        return $this->hasOne('App\Driver','CardID','card_id');
    }

    public function truck()
    {
        return $this->hasOne('App\Truck','CardID','card_id');
    }

}
