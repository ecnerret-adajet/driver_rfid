<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Session;

class GateEntry extends Model
{
    protected $table = 'gate_entries';

    protected $connection = "sqlsrv";

    protected $dates = [
        'LocalTime',
    ];

    public function getDates()
    {
        return [];
    }

    protected $fillable  = [
        'gate_number',
        'driver_name',
        'avatar',
        'plate_number',
        'hauler_name',
        'driverqueue_id',
        'LogID',
        'CardholderID',
        'shipment_number',
        'isShipmentStarted',
        'LocalTime',
        'driver_availability',
        'truck_availability',
    ];

    // Relationships Tables

    public function shipment() {
        return $this->belongsTo(Shipment::class,'shipment_number','shipment_number');
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

    //Custom Eleqouent for Report

    public function queueEntry()
    {
        return $this->belongsTo(QueueEntry::class, 'CardholderID', 'CardholderID')
            ->whereDate('LocalTime', Session::get('date'));
    }

    public function hasShipment()
    {
        return $this->belongsTo(Shipment::class,'CardholderID','CardholderID')
            ->whereDate('change_date', Session::get('date'));
    }

    public function hasTruckscaleIn()
    {
        return $this->belongsTo(Log::class,'CardholderID','CardholderID')
                ->where('Direction',1)
                ->whereIn('ControllerID', [4,8]) //Controller for TRUCKSCALE IN
                ->whereIn('DoorID',[0,1])
                ->whereDate('LocalTime', Session::get('date'));
            //    ->where('LocalTime', '>=', Carbon::parse($this->created_at)->subHours(24)->toDateTimeString());
    }

    public function hasTruckscaleOut()
    {
        return $this->belongsTo(Log::class,'CardholderID','CardholderID')
            ->where('Direction',2)
            ->whereIn('ControllerID', [4,8]) //Controller for TRUCKSCALE OUT
            ->whereIn('DoorID',[0,1])
            ->whereDate('LocalTime', Session::get('date'));
            // ->where('LocalTime', '>=', Carbon::parse($this->created_at)->subHours(24)->toDateTimeString());
    }

    public function hasGateOut()
    {
        return $this->belongsTo(Log::class,'CardholderID','CardholderID')
            ->where('Direction',2)
            ->whereIn('ControllerID', [4,6,9]) //Controller for Gate OUT
            ->whereDate('LocalTime', Session::get('date'));
            // ->whereBetween('LocalTime', [$this->LocalTime, Carbon::parse($this->LocalTime)]);
    }

    // Query Scope

    public function scopeCheckIfTappedFromGate($query, $CardholderID) 
    {
        //should DRIVER THAT TAP less 3 hours from gate RFID
        return $query->where('CardholderID', $CardholderID)
                    ->where('LocalTime', '>=',  Carbon::today()->subHours(3)->toDateTimeString())
                    ->first(); 
    }

}

