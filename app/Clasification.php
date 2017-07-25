<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clasification extends Model
{
    protected $connection = "sqlsrv_four";
    protected $fillable = [
        'name'
    ];

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }
}
