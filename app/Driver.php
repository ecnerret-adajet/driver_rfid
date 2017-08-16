<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
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
    ];

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

}
