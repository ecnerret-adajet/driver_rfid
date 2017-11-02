<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Carbon\Carbon;

class Driver extends Model
{

    use LogsActivity;

    protected $connection = "sqlsrv";
    protected $fillable = [
    	'avatar',
    	'name',
        'driver_number',
    	'phone_number',
    	'substitute',
        'cardholder_id',
        'remarks',
        'print_status',
        'update_count',
        'notif_status',
        'driver_license',
        'nbi_number',
        'address',
        'contact_person',
        'contact_phone',
        'start_validity_date',
        'end_validity_date'
    ];

    protected static $logAttributes = [
        'name', 
        'cardholder_id',
    ];

    protected $dates = [
        'start_validity_date',
        'end_validity_date'
    ];


      /**
     * Dates configuration for validity_start_date
     */
     public function setStartValidityDateAttribute($date)
     {
         $this->attributes['start_validity_date'] = Carbon::parse($date);
     }
 
     public function getStartValidityDateAttribute($date)
     {
         return Carbon::parse($date)->format('Y-m-d');
     }

    /**
     * Dates configuration for validity_end_date
     */
     public function setEndValidityDateAttribute($date)
     {
         $this->attributes['end_validity_date'] = Carbon::parse($date);
     }
 
     public function getEndValidityDateAttribute($date)
     {
         return Carbon::parse($date)->format('Y-m-d');
     }

    /**
     * driver model has a user authenticated belongsto relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cardholder()
    {
        return $this->belongsTo('App\Cardholder','cardholder_id','CardholderID');
    }

    public function card()
    {
        return $this->belongsTo('App\Card','card_id','CardID');
    }

    // public function getCardholderListAttribute()
    // {
    //     return $this->cardholder->pluck('CardholderID')->all();
    // }

    public function log()
    {
        return $this->belongsTo('App\Log','cardholder_id','CardholderID');
    }

    /**
     * driver will have a assigned hauler
     */
    public function haulers()
    {
        return $this->belongsToMany('App\Hauler');
    }

    public function getHaulerListAttribute()
    {
        return $this->haulers->pluck('id')->all();
    }

    /**
     * driver wiill assign a truck plate number
     */
    public function trucks()
    {
        return $this->belongsToMany('App\Truck');
    }

    public function getTruckListAttribute()
    {
        return $this->trucks->pluck('id')->all();
    }

    /**
     * Driver transfer history
     */
    public function transfers()
    {
        return $this->hasMany('App\Transfer');
    }


    /**
    * Clasification to determine whether TRANSFER or LOST RIFID
    */
    public function clasification()
    {
        return $this->belongsTo('App\Clasification');
    }

    /**
    * Confirm approval from RTC supervisor
    */
    public function confirms()
    {
        return $this->hasMany(Confirm::class);
    }
    
    /**
    * Logs all revisions from drivers record
    */
    public function versions()
    {
        return $this->belongsToMany(Verson::class);
    }
    
    public function getVersionListAttribute()
    {
        return $this->versions->pluck('id')->all();
    }

    /**
    *
    * Get all Cards to link
    */
    public function binders()
    {
        return $this->belongsToMany('App\Binder');
    }

    public function getBinderListAttribute()
    {
        return $this->binders()->pluck('id')->all();
    }

    public function monitor()
    {
        return $this->hasOne('App\Monitor');
    }

    /**
     * 
     *  Driver version
     * 
     */
    public function driverversion()
    {
        return $this->belongsTo('App\Driverversion');
    }

    /**
     *  
     *  Generate From this month
     * 
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', Carbon::now()->month);
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()]);
    }

    /**
     * 
     *  Driver's assocaited with driver's pass
     * 
     */
    public function passes()
    {
        return $this->hasMany(Pass::class);
    }


}
