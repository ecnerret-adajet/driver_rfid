<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rfid extends Model
{
    protected $connection = "sqlsrv";
    protected $fillable = [
        'name'
    ];

    public function cards()
    {
        return $this->hasMany('App\Card','CardID','id');
    }

    public function binders()
    {
        return $this->hasMany('App\Binder');
    }

}
