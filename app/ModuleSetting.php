<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleSetting extends Model
{
    protected $fillable = [
        'modelable_type',
        'modelable_array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
