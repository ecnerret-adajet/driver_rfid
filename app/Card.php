<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $connection = "sqlsrv_two";
    protected $table = "Card";

    public function cardholders()
    {
    	return $this->hasMany(Cardholder::class,'CardholderID','CardholderID');
    }

    public function binders() 
    {
        return $this->hasMany('App\Binder','card_id','CardID');
    }

}
