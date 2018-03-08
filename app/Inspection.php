<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $fillable = ['remarks','truck_id','status']; 
    protected $guarded = ['id','user_id'];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
