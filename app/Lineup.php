<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lineup extends Model
{
    protected $fillable = [
        'availability',
        'remarks',
        'hustling',
        'approval_notif',
        'status',
        'availability'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function log()
    {
        return $this->belongsTo('App\Log','LogID','LogID');
    }
}
