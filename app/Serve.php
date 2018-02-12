<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serve extends Model
{
    protected $fillable = [
        'on_serving',
    ];

    public function getDates()
    {
        return [];
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
