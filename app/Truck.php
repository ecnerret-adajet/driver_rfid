<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $connection = "sqlsrv";
    protected $fillable = [
    	'plate_number',
        'availability',
        'reg_number',
        'contract_code',
        'contract_description',
        'vendor_description',
        'subvendor_description',
        'validity_start_date',
        'validity_end_date',
    ];

    public function drivers()
    {
    	return $this->belongsToMany(Driver::class);
    }

    // list all associated hauler in trucks
    public function haulers()
    {
        return $this->belongsToMany('App\Hauler');
    }

    public function getHaulerListAttribute()
    {
        return $this->haulers->pluck('id')->all();
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

    /**
    *
    * Associate Sticker RFID to truck
    */
    public function card()
    {
        return $this->belongsTo('App\Card','card_id','CardID');
    }

    /**
    *
    * Associate capacities categories to truck
    *
    */
    public function capacity()
    {
        return $this->belongsTo('App\Capacity');
    }
}
