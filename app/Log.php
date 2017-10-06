<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Log extends Model
{
    protected $connection = "sqlsrv_three";
    protected $table = "AccessLog2";

    protected $dates = ['LocalTime'];

    protected $hidden = [
        'MsgID',
        'CardBits',
        'CardCode',
        'CardType',
        'DoorID',
        'Invalid',
    ];

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
    	return $this->hasOne('App\Driver','cardholder_id','CardholderID');
    }

    public function lineups()
    {
        return $this->hasMany('App\Lineup','LogID','LogID');
    }

    public function customers()
    {
        return $this->hasMany('App\Customer','log_ID','LogID');
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
}
