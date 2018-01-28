<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'avatar'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    public function driver()
    {
        return $this->hasOne('App\Driver');
    }
}
