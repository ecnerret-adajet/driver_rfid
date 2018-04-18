<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Carbon\Carbon;
use DB;

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

    public function getDates()
    {
        return [];
    }

    protected $dates = [
        'validity_start_date',
        'validity_end_date'
    ];

    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at',
        'capacity_id',
        'vendor_number',
        'subvendor_number',
        'contract_code',
        'contract_description',
        'vendor_description',
        'subvendor_description',
        'validity_start_date',
        'validity_end_date',
        'base_id',
        'plant_id',
    ];

    /**
     * Add User who added a truck
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     *  Truck has a inspection model
     */
    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    /**
     * Remove unneccessary character in plate_number
     */
    public function setPlateNumberAttribute($value)
    {
        $this->attributes['plate_number'] = str_replace('_',' ',$value);
    }
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

    public function driver()
    {
    	return $this->belongsToMany('App\Driver');
    }

    public function getDriverAttribute()
    {
        return $this->driver()->first();
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

    // Search by one hauler only
    public function hauler()
    {
        return $this->belongsToMany('App\Hauler')->select('name');
    }

    public function getHaulerAttribute()
    {
        return $this->hauler()->first();
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

    /**
     * 
     *  Get all truck for current month
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
     * Sanitize plate number
     */
    public function getPlatenumFormatAttribute()
    {
        $x = str_replace('-',' ',strtoupper($this->plate_number));
        $z = str_replace('_','',$x);
        return $z;
    }

    /**
     *  Format MV plate numbers
     */
    public function getPlateNumberAttribute($value)
    {
        // check also if a plate number starts with MVX-000 
        // $hasMV =  str_is('MV*', $value) ? str_replace('MV', 'MV ', $value) : $value; 

        if(str_is('MV*', $value)) {
            return str_replace('MV', 'MV ', $value);
        } elseif (str_is('MV-*', $value)) {
            return str_replace('MV-', 'MV ', $value);
        } else {
            return $value;
        }
        
    
    }

    /**
     * Search Plate Number / Driver if DR was completely submitted.
     */
    public function scopeCallLastTrip($query, $plateNumber)
    {
        $plate_format = str_replace('-',' ',$plateNumber);
        $recievedDR = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$plate_format','deploy')");
        $getFirst = head($recievedDR);
        $result = $getFirst == false ? "UNPROCESS" : $getFirst->submission_date;


        return $result;  
    }

    /**
     * Search Plate Number if DR was completely submitted pluck cardholder
     */
    public function scopeCallLastTripCardholder($query, $plateNumberArray)
    {
        $cardArray = array();

        // Search for DR submitted with plate number
        $x = str_replace('-',' ',strtoupper($plateNumberArray));
        $z = str_replace('_','',$x);
        $recievedDR = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");

        //Found plate number will now be converted and pluck to cardholder ID
        if(count($recievedDR) != 0) {
            $plate_has_dr = array_pluck($recievedDR, 'plate_number');

            // $formated_plate_num = str_is('MV*', $plate_has_dr) ? str_replace(' ','', $plate_has_dr) : str_replace(' ','-', $plate_has_dr); 

             // $hasMV =  str_is('MV*', $value) ? str_replace('MV', 'MV ', $value) : $value; 
            
            array_push($cardArray, $plate_has_dr);
        }


        $plate_number_array = array_collapse($cardArray);

        $cardholder = $query->whereIn('plate_number',$plate_number_array)
                            ->with('driver')
                            ->get()
                            ->pluck('driver.cardholder_id');

        return $cardholder;
        
    }
    

}
