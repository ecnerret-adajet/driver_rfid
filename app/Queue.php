<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'availability',
        'remarks'
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

