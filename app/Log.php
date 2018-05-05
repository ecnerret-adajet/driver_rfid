<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Cardholder;
use App\Driverqueue;

class Log extends Model
{
    protected $connection = "sqlsrv_three";
    protected $table = "AccessLog";
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
                      ->whereDate('LocalTime', '>=', Carbon::today())
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
                      ->whereBetween('LocalTime', [Carbon::today()->subDays(1), Carbon::today()])
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
                      ->whereDate('LocalTime',  Carbon::today())
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
                     ->whereDate('LocalTime', Carbon::today())
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
                    ->whereDate('LocalTime', Carbon::today())
                    ->orderBy('LocalTime','DESC')->get();

    }

    // latest scoped methods


    /**
     *  Get all drivers who has a truckscale IN within cureent date in Manila
     * 
     * Pluck Return
     * 
     */
    public function scopeTruckscaleIn($query)
    {
         return  $query->select('CardholderID')
                ->where('ControllerID', 4)
                ->where('Direction',1) // All Truckscale In
                ->where('CardholderID', '>=', 15)
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
                ->where('CardholderID', '>=', 15)
                ->whereDate('LocalTime', Carbon::today())
                ->pluck('CardholderID');
    }

    /**
     * Get all drivers who has truckscale out in lapaz within current date
     * 
     * Pluck Return
     */
    public function scopeLpzTruckscaleOut($query)
    {
        return  $query->select('CardholderID')
                ->where('ControllerID', 6) // lapaz controller
                ->where('Direction',2) // All Truckscale Out
                ->whereDate('LocalTime', Carbon::today())
                ->pluck('CardholderID');
    }

    /**
     * 
     * Get all driver who has truckscale IN withtin BTN MGC wihtin current date
     * 
     */
    public function scopeBtnTruckscaleIn($query)
    {
         return  $query->select('CardholderID')
                ->where('ControllerID', 8) // bataan controller
                ->where('Direction',1) // All Truckscale In
                ->whereDate('LocalTime', Carbon::today())
                ->pluck('CardholderID');
    }

    /**
     *  Get all drivers who has truckscale out in BTN MGC within current date
     */
    public function scopeBtnTruckscaleOut($query)
    {
        return  $query->select('CardholderID')
                ->where('ControllerID', 8) // bataan controller
                ->where('Direction',2) // All Truckscale Out
                ->whereDate('LocalTime', Carbon::today())
                ->pluck('CardholderID');
    }

    public function scopeThisDay($query)
    {
        return $query->whereDate('LocalTime', '>=', Carbon::today());
        
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
     * Get Drivers tapped from gate RFID based from location parameter within the date
     * 
     */

     public function scopeBarrierLocation($query, $door, $controller)
     {
         return $query->select('LogID','CardholderID','LocalTime')
                ->whereDate('LocalTime', Carbon::today())
                ->whereIn('DoorID',[$door])
                ->whereNotIn('CardholderID',$this->barrierNoDriver())
                ->where('ControllerID', $controller)
                ->where('CardholderID', '>=', 15)
                ->orderBy('LocalTime','DESC')
                ->with('driver')
                ->pluck('CardholderID');
     }

    
    public function scopeGateLocation($query, $driverqueue)
    {

        $queue = Driverqueue::where('id',$driverqueue)->first();

         return $query->select('LogID','CardholderID','LocalTime')
                ->whereDate('LocalTime', Carbon::today())
                ->whereIn('DoorID',[$queue->gate->door])
                ->whereNotIn('CardholderID',$this->barrierNoDriver())
                ->where('ControllerID', $queue->gate->controller)
                ->where('CardholderID', '>=', 15)
                ->orderBy('LocalTime','DESC')
                ->with('driver')
                ->pluck('CardholderID');
     }


     /**
      * Get Object for drivers who tapped from gate RFID based fom location
      */
    public function scopeBarrierLocationObject($query, $door, $controller, $date)
     {
         $checkDate = !empty($date) ? Carbon::parse($date) : Carbon::today();
         
         return $query->select('LogID','CardholderID','LocalTime')
                ->whereDate('LocalTime', '=', $checkDate)
                ->where('DoorID',$door)
                ->whereNotIn('CardholderID',$this->barrierNoDriver())
                ->where('ControllerID', $controller)
                ->where('CardholderID', '>=', 15)
                ->with('driver')
                ->get();
     }


     /**
      * Get Drivers tapped from queueing RFID based from location parameter
      */
      public function scopeQueueLocation($query, $door, $controller, $hasTruckscaleOut, $locationGate, $date)
      {
        $checkDate = !empty($date) ? Carbon::parse($date) : Carbon::today();

        return $query->with(['drivers','drivers.truck','drivers.hauler','driver.serves'])
                ->where('ControllerID', $controller)
                ->where('DoorID',$door)
                ->whereIn('CardholderID',$locationGate)
                ->whereNotIn('CardholderID',$hasTruckscaleOut)
                ->whereDate('LocalTime', '=', $checkDate)
                ->orderBy('LogID','ASC')
                ->get();
      }

      /**
       * Get Drivers tapped from queue RFID based from location parameter without locationgate
       */
      public function scopeQueueLocationX($query, $door, $controller, $hasTruckscaleOut, $date)
      {
        $checkDate = !empty($date) ? Carbon::parse($date) : Carbon::today();

        return $query->with(['drivers','drivers.truck','drivers.hauler','driver.serves'])
                ->where('ControllerID', $controller)
                ->where('DoorID',$door)
                ->whereNotIn('CardholderID',$hasTruckscaleOut)
                ->whereDate('LocalTime', '=', $checkDate)
                ->orderBy('LogID','ASC')
                ->get();
      }

        /**
       * Get Driver queue tap to Monitor Screen in the truckscale office
       */
      public function scopeDriverQueueingLocation($query, $controller, $door, $locationGate, $checkTruckscaleOut)
      {
          return $query->with(['drivers','drivers.truck','drivers.hauler','driver.serves','driver.image'])
                            ->where('ControllerID', $controller)
                            ->where('DoorID',$door)
                            ->whereIn('CardholderID',$locationGate)
                            ->whereNotIn('CardholderID',$checkTruckscaleOut)
                            // ->whereDate('LocalTime', Carbon::today())
                            ->orderBy('LogID','DESC')
                            ->take(20)
                            ->get();
      }

      /**
       * Get Trucks whose currently in the plant, but no truckscale out
       */
      public function scopeTrucksInPlant($query, $direction, $controller, $hasTruckscaleOut)
      {
        return $query->select('CardholderID')
                    ->where('ControllerID', $controller)
                    ->where('Direction',$direction)
                    ->whereNotIn('CardholderID',$hasTruckscaleOut)
                    ->whereDate('LocalTime', Carbon::today())
                    ->pluck('CardholderID');
      }

      /**
       * Dynamically get truckscale out for a particular location within the day
       */
      public function scopeTruckscaleOutLocation($query, $controller)
      {
            return $query->select('CardholderID')
                ->where('CardholderID', '>=', 15)
                ->where('ControllerID', $controller)
                ->where('Direction',2) // All Truckscale Out
                ->whereDate('LocalTime', Carbon::today())
                ->pluck('CardholderID');

      }

    public function scopeTruckscaleOutFromQueue($query, $driverqueue)
    {
        $queue = Driverqueue::where('id',$driverqueue)->first();

        return  $query->select('CardholderID')
                ->where('ControllerID', $queue->ts_out_controller)
                ->where('Direction',2) // All Truckscale Out
                ->where('CardholderID', '>=', 15)
                ->whereDate('LocalTime', Carbon::today())
                ->pluck('CardholderID');
    }

      /**
       *  Export Truckscale search by date
       */
      public function scopeTruckscaleOutLocationDate($query, $controller, $date)
      {
          $checkDate = !empty($date) ? Carbon::parse($date) : Carbon::today();
          return $query->groupBy('CardholderID')
                ->select('CardholderID')
                ->where('CardholderID', '>=', 15)
                ->where('ControllerID', $controller)
                ->where('Direction',2) // All Truckscale Out
                ->whereDate('LocalTime', $checkDate)
                ->pluck('CardholderID');
      }

    /**
    * Pluck all plate number in MNL Gate to store to shipments table
    */
    public function scopeQueueLocationShipment($query, $door, $controller, $hasTruckscaleOut, $date)
      {
        $checkDate = !empty($date) ? Carbon::parse($date) : Carbon::today();

        return $query->with(['driver','driver.truck','driver.hauler'])
                ->where('ControllerID', $controller)
                ->where('DoorID',$door)
                ->whereNotIn('CardholderID',$hasTruckscaleOut)
                ->whereDate('LocalTime', '=', $checkDate)
                ->orderBy('LogID','ASC')
                ->get();
      }

      /**
      * Scope queries for queeuing screens
      */

    /**
     * Get Drivers tapped from gate RFID based from location parameter within the date
     * 
    */
     public function scopeBarrierLocationRecent($query, $door, $controller)
     {
         return $query->select('LogID','CardholderID','LocalTime')
                // ->whereDate('LocalTime', Carbon::today())
                ->whereIn('DoorID',[$door])
                ->whereNotIn('CardholderID',$this->barrierNoDriver())
                ->where('ControllerID', $controller)
                ->where('CardholderID', '>=', 15)
                ->orderBy('LocalTime','DESC')
                ->with('driver')
                ->take(20)
                ->pluck('CardholderID');
     }

    public function scopeTruckscaleOutRecent($query)
    {
       return  $query->select('CardholderID')
                ->where('ControllerID', 4)
                ->where('Direction',2) // All Truckscale Out
                // ->whereDate('LocalTime', Carbon::today())
                ->take(20)
                ->pluck('CardholderID');
    }

    public function scopeBtnTruckscaleOutRecent($query)
    {
        return  $query->select('CardholderID')
                ->where('ControllerID', 8) // bataan controller
                ->where('Direction',2) // All Truckscale Out
                // ->whereDate('LocalTime', Carbon::today())
                ->take(20)
                ->pluck('CardholderID');
    }

    /**
     * Get the last driver tapped from queue rfid
     */
    public function scopeLastDriver($query, $door, $controller)
    {
       return $query->with('drivers','drivers.image','drivers.truck','drivers.hauler')
            ->where('ControllerID', $controller)
            ->where('DoorID',$door)
            ->orderBy('LogID','DESC')
            ->take(1)
            ->get();
    }

    /**
     * Get the last driver tapped from queue rfid - cardholder only
     */
    public function scopeLastDriverCardholder($query, $door, $controller)
    {
       return $query->where('ControllerID', $controller)
            ->where('DoorID',$door)
            ->orderBy('LogID','DESC')
            ->take(1)
            ->pluck('CardholderID');
    }
    
    public function scopeLastDriverFromQueue($query, $driverqueue)
    {

        $queue = Driverqueue::where('id',$driverqueue)->first();

       return $query->where('ControllerID', $queue->controller)
            ->where('DoorID',$queue->door)
            ->orderBy('LogID','DESC')
            ->take(1)
            ->pluck('CardholderID');
    } 

}
