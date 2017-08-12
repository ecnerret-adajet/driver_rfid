<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Binder extends Model
{

    protected $connection = "sqlsrv";
    
    public function card()
    {
        return $this->belongsTo('App\Card','card_id','CardID');
    }

    public function rfid()
    {
        return $this->belongsTo('App\Rfid');
    }

}
