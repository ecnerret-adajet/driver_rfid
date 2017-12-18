<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'avatar'
    ];

    public function driver()
    {
        return $this->hasOne('App\Driver');
    }
}
