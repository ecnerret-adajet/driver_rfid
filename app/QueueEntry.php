<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class QueueEntry extends Model
{
    protected $table = 'queue_entries';

    protected $connection = "sqlsrv";

    protected $dates = ['LocalTime'];

    protected $fillable = [
        'queue_number',
        'driver_name',
        'avatar',
        'truck_id',
        'plate_number',
        'hauler_name',
        'driverqueue_id',
        'shipment_number',
        'LogID',
        'CardholderID',
        'LocalTime',
        'isDRCompleted',
        'driver_availability',
        'truck_availability',
        'isTappedGateFirst',
        'isSecondDelivery',
        'isShipmentStarted',
    ];

    protected $hidden = [
        'updated_at',
        'truck_id',
        'isShipmentStarted'
    ];

    public function getDates()
    {
        return [];
    }

    // Attributes Function
    public function setPlateNumberAttribute($value)
    {
        $this->attributes['plate_number'] = str_replace('_', '', $value);
    }

    // Relationships Model
    public function truck() {
        return $this->belongsTo(Truck::class);
    }
    
    public function shipment() {
        return $this->belongsTo(Shipment::class,'CardholderID','CardholderID')
                    ->whereDate('created_at', Carbon::parse($this->created_at));
    }

    public function todayShipment()
    {
        return $this->belongsTo(Shipment::class,'CardholderID','CardholderID');
    }

    public function log() {
        return $this->belongsTo(Log::class, 'LogID','LogID');
    }

    public function cardholder() {
        return $this->belongsTo(Cardholder::class, 'CardholderID','CardholderID');
    }

    public function driverqueue() {
        return $this->belongsTo(Driverqueue::class);
    }

    public function gateEntry() {
        return $this->belongsTo(GateEntry::class);
    }

    //Query Scoped
    public function scopeTotalAssigned($query, $driverqueue)
    {
        return $query->with('shipment')
                    ->whereDate('created_at',Carbon::today())
                    ->where('driverqueue_id',$driverqueue)
                    ->has('shipment')
                    // ->whereNotNull('shipment_number')
                    ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                    ->get()
                    ->unique('CardholderID');
    }

    public function scopeTotalOpen($query, $driverqueue)
    {
        return $query->whereDate('created_at',Carbon::today())
                    ->doesntHave('shipment')
                    ->where('driverqueue_id',$driverqueue)
                    // ->whereNull('shipment_number')
                    ->whereNotNull('driver_availability')
                    ->whereNotNull('truck_availability')
                    ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                    ->whereNotNull('isTappedGateFirst')
                    ->get()
                    ->unique('CardholderID');
    }


}
