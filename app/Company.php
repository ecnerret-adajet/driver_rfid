<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     *  List user where has a user_id assigned
     * 
     */
    public function user()
    {
        return $this->hasOne('App\User');
    }

}
