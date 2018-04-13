<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Serve extends Model
{
    protected $table = 'serves';

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

    /**
     * Get All Served driver pluck only cardholder
     */
    public function scopeServedTodayCardholder($query)
    {
         return $query->where('on_serving',1)
                ->orderBy('id','DESC')
                ->whereDate('created_at', Carbon::today())
                ->with('driver','driver.cardholder')
                ->get()
                ->pluck('driver.cardholder.CardholderID');
    }

    public function scopeIsDriverShipped($query, $driverID)
    {
        return $query->with('driver')
                    ->where('on_serving',1)
                    ->whereHas('driver', function($q) use ($driverID){
                        $q->where('id',$driverID);
                    })
                    ->whereDate('created_at',Carbon::today())
                    ->take(1)
                    ->get();
    }
}
