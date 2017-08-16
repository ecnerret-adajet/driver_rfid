<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Binder extends Model
{

    protected $connection = "sqlsrv";

    protected $fillable = [
        'card_id',
        'rfid_id',
        'cardholder_id'
    ];
    
    public function card()
    {
        return $this->belongsTo('App\Card','card_id','CardID');
    }

    public function cardholder()
    {
        return $this->belongsTo('App\Cardholder','cardholder_id','CardholderID');
    }

    public function rfid()
    {
        return $this->belongsTo('App\Rfid');
    }

    public function drivers()
    {
        return $this->belongsToMany('App\Driver');
    }

    public function trucks()
    {
        return $this->belongsToMany('App\Truck');
    }

}
