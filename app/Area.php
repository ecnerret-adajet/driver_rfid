<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'name'
    ];

    public function gate()
    {
        return $this->hasOne(Gate::class);
    }
}
