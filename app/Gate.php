<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gate extends Model
{
    protected $fillable = [
        'title',
        'door',
        'controller',
        'area_id'
    ];

    protected $dates = [
        'deleted_at'
    ];

    /**
     * Linked to a user who create a gate RFID
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
    * Gate's RFID Location
     */
    public function area()
    {
        return $this->belongsTo(Area::class,'area_id');
    }

}
