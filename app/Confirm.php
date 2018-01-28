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

    protected $hidden = [
        'remarks',
        'created_at',
        'updated_at',
        'driver_id',
        'user_id',
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
