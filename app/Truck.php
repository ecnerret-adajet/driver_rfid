<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Carbon\Carbon;

class Truck extends Model
{

    use LogsActivity;

    protected $connection = "sqlsrv";
    protected $fillable = [
    	'plate_number',
        'availability',
        'reg_number',
        'contract_code',
        'contract_description',
        'vendor_description',
        'vendor_number',
        'subvendor_number',
        'subvendor_description',
        'validity_start_date',
        'validity_end_date',
        'documents',
    ];

    protected static $logAttributes = [
        'plate_number', 
        'availability',
        'vendor_description',
        'subvendor_description',
        'validity_start_date',
        'validity_end_date',
    ];

    protected $dates = [
        'validity_start_date',
        'validity_end_date'
    ];

    /**
     * Dates configuration for validity_start_date
     */
     public function setValidityStartDateAttribute($date)
     {
         $this->attributes['validity_start_date'] = Carbon::parse($date);
     }
 
     public function getValidityStartDateAttribute($date)
     {
         return Carbon::parse($date)->format('Y-m-d');
     }

    /**
     * Dates configuration for validity_end_date
     */
     public function setValidityEndDateAttribute($date)
     {
         $this->attributes['validity_end_date'] = Carbon::parse($date);
     }
 
     public function getValidityEndDateAttribute($date)
     {
         return Carbon::parse($date)->format('Y-m-d');
     }

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

    //list all assicaited contract code in truck 
    public function contracts()
    {
        return $this->belongsToMany('App\Contract');
    }
     
    public function getContractListAttribute()
    {
        return $this->contracts->pluck('id')->all();
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

    public function base()
    {
        return $this->belongsTo('App\Base');
    }

    public function plant()
    {
        return $this->belongsTo('App\Plant');
    }

    //get plant multi select field

    public function plants()
    {
        return $this->belongsToMany('App\Plant');
    }

    public function getPlantListAttribute()
    {
        return $this->plants()->pluck('id')->all();
    }
}
