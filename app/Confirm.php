<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Confirm extends Model
{
    protected $connection = "sqlsrv";
    protected $fillable = [
        'status',
        'remarks',
        'classification'
    ];

    public function getDates()
    {
        return [];
    }

    public function driver()
    {
        return $this->belongsTo('App\Driver');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
