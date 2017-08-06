<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $connection = "sqlsrv";

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
