<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Cardholder;

class Log extends Model
{
    protected $connection = "sqlsrv_three";
    protected $table = "AccessLog2";
    public $timestamps = false;

    protected $dates = ['LocalTime'];

    protected $hidden = [
        'MsgID',
        'CardBits',
        'CardCode',
        'CardType',
        'DoorID',
        'Invalid',
    ];
    
    public function getDates()
    {
        return [];
    }

    public function getKeyName(){
        return "CardholderID";
    }

    public function cardholders()
    {
    	return $this->hasMany('App\Cardholder','CardholderID','CardholderID');
    }

    public function drivers()
    {
    	return $this->hasMany('App\Driver','cardholder_id','CardholderID');
    }

    public function driver()
    {
    	return $this->hasMany('App\Driver','cardholder_id','CardholderID');
    }

    public function getDriverAttribute()
    {
        return $this->driver()->first();
    }

    public function lineups()
    {
        return $this->hasMany('App\Lineup','LogID','LogID');
    }

    public function customers()
    {
        return $this->hasMany('App\Customer','log_ID','LogID');
    }

    public function passes()
    {
        return $this->hasMany('App\Pass','LogID','LogID');
    }

    public function monitors()
    {
        return $this->hasMany('App\Monitor','log_ID','LogID');
    }

    public function getLocalTimeAttribute($date)
    {
        return Carbon::parse($date);
    }

    //Scope Queries

    public function scopeMatch($query, $current)
    {
        return $query->where('CardholderID', '>=', 1)
                     ->where('LogID', '<', $current)
                     ->where('LogID', '>=', $current-5)
                     ->orderBy('LogID','DESC');
    }

    public function scopePickupIn($query, $pickup_card, $created_date)
    {
        return $query->where('CardholderID', $pickup_card)
                     ->where('Direction', 1)
                     ->where('LocalTime', '>', Carbon::parse($created_date))
                     ->where('LocalTime', '<=', Carbon::parse($created_date)->addHour())
                     ->take(1);
    }

    public function scopePickupOut($query, $pickup_card, $created_date)
    {
        return $query->where('CardholderID', $pickup_card)
                     ->where('Direction', 2)
                     ->whereDate('LocalTime', $created_date)
                     ->take(1);
    }

    public function scopeCheckTrip($query, $card, $date)
    {
        return $query->where('CardholderID',$card)
					->whereDate('LocalTime' , Carbon::parse($date))
					->orderBy('LocalTime','ASC');
    }

    // Search Today's Entires
    /*
    * Partial Entries / Get only for current date both in and out
    *
    */
    public function scopeEnties($query)
    {
        $pickups = Cardholder::select('CardholderID')
                                   ->where('Name','LIKE','%Pickup%')
                                   ->get();

        return  $query->whereNotIn('ControllerID',[1])
                      ->whereNotIn('CardholderID',$pickups)
                      ->where('CardholderID', '>=', 1)
                      ->whereDate('LocalTime', '>=', Carbon::now())
                      ->orderBy('LocalTime','DESC')->get(); 
    }

    /*
    * Get Full "in" entries - within today subtract one day
    *  - MonitorsController > create, edit
    *  - LogsController > index
    */
    public function scopeFullEntriesIn($query) 
    {
        return $query->where('CardholderID', '>=', 1)
                      ->where('Direction', 1)
                      ->whereBetween('LocalTime', [Carbon::now()->subDays(1), Carbon::now()])
                      ->orderBy('LocalTime','DESC')->get();
    }

    /*
    * Get Full "out" entries - within current date
    * - MonitorsController > create , edit
    * - LogsControllser > index
    */
    public function scopeFullEntriesOut($query)
    {
        return $query->where('CardholderID', '>=', 1)
                      ->where('Direction', 2)
                      ->whereDate('LocalTime',  Carbon::now())
                      ->orderBy('LocalTime','DESC')->get();

    }

    /*
    *
    * Get current day logs except for pickup cardholders
    *
    */
    public function scopeLogsNoPickup($query, $pickup)
    {
        return $query->whereNotIn('ControllerID',[1])
                     ->whereNotIn('CardholderID',$pickup)
                     ->where('CardholderID', '>=', 1)
                     ->whereDate('LocalTime', Carbon::now())
                     ->orderBy('LocalTime','DESC')->get();
    }

    /*
    *
    * Get all logs from truckscale reader
    *
    */
    public function scopeQueue($query)
    {
        return $query->where('ControllerID',1)
                    ->where('DoorID',0)
                    ->where('CardholderID', '>=', 15)
                    ->whereDate('LocalTime', Carbon::now())
                    ->orderBy('LocalTime','DESC')->get();

    }

    // latest scoped methods


    /**
     *  Get all drivers who has a truckscale IN within cureent date
     * 
     * Pluck Return
     * 
     */
    public function scopeTruckscaleIn($query)
    {
         return  $query->select('CardholderID')
                ->where('ControllerID', 4)
                ->where('Direction',1) // All Truckscale In
                ->whereDate('LocalTime', Carbon::today())
                ->pluck('CardholderID');
    }

    /**
     * Get all Drivers who has a truckscale OUT within current date
     * 
     * Pluck Return
     * 
     */
    public function scopeTruckscaleOut($query)
    {
       return  $query->select('CardholderID')
                ->where('ControllerID', 4)
                ->where('Direction',2) // All Truckscale Out
                ->whereDate('LocalTime', Carbon::today())
                ->pluck('CardholderID');
    }

    public function scopeThisDay($query)
    {
        return $query->whereDate('LocalTime', '>=', Carbon::now());
        
    }

    // Get Driver from barrier that is not Driver's RFID in the system
    private function barrierNoDriver()
    {
        $pickup_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%pickup%')
        ->pluck('CardholderID'); 

        $guard_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%GUARD%')
        ->pluck('CardholderID'); 

        $executive_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%EXECUTIVE%')
        ->pluck('CardholderID'); 

        // Remove all cardholder without driver assigned
        $not_driver = array_collapse([$pickup_cards, $guard_cards, $executive_cards]);
        
        return $not_driver;
    }

    /**
     * Get Drivers tapped from gate RFID based from location parameter
     * 
     */

     public function scopeBarrierLocation($query, $door, $controller)
     {
         return $query->select('LogID','CardholderID')
                ->whereDate('LocalTime', Carbon::today())
                ->whereIn('DoorID',[$door])
                ->whereNotIn('CardholderID',$this->barrierNoDriver())
                ->where('ControllerID', $controller)
                ->where('CardholderID', '>=', 15)
                ->orderBy('LocalTime','DESC')
                ->with('driver')
                ->pluck('CardholderID');
     }
}
