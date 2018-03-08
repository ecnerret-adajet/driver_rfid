<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Serve extends Model
{
    protected $fillable = [
        'on_serving',
    ];

    public function getDates()
    {
        return [];
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
    // Scoped Functions


    /**
     *  Get All Served Trucks in Current Date
     * 
     * @return Pluck
     * 
     */
    public function scopeServedToday($query)
    {
        return $query->where('on_serving',1)
                ->orderBy('id','DESC')
                ->whereDate('created_at', Carbon::today())
                ->pluck('driver_id');
    }
}
