<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pass extends Model
{
    protected $filllable = [
        'remarks',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function log()
    {
        return $this->belongsTo('App\Log','LogID','LogID');
    }
}
