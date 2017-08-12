<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $connection = "sqlsrv";
    protected $fillable = [
        'key',
        'old_value',
        'new_value',
        'start_validity_date',
        'end_validity_date'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function drivers()
    {
        return $this->belongsToMany(Driver::class);
    }

}
