<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clasification extends Model
{
    protected $connection = "sqlsrv";
    protected $fillable = [
        'name'
    ];

    public function driver()
    {
        return $this->hasMany(Driver::class);
    }
}
