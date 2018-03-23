<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driverqueue extends Model
{
    protected $fillable = [
        'title',
        'door',
        'controller',
        'ts_out_controller'
    ];

    protected $dates = [
        'deleted_at'
    ];

    /**
     * Linked to a user who create a queue monitoring
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Queue's location 
     */
    public function area()
    {
        return $this->belongsTo(Area::class,'area_id');
    }

    /**
     * Queue's Gate Location
     */
    public function gate()
    {
        return $this->belongsTo(Gate::class,'gate_id');
    }


}
