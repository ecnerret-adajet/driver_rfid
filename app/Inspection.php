<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{

    /**
     * Legend:
     * 
     * 5 - Manila Plant
     * 6 - Lapaz Plant
     * 7 - Bataan Plant
     * 
     */

    protected $fillable = [
        'remarks',
        'truck_id',
        'status'
    ];

    protected $guarded = ['id','user_id'];

    //Relationship Model
    public function accessLocation()
    {
        return $this->belongsTo(AccessGroup::class,'access_location','AccessGroupID');
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
