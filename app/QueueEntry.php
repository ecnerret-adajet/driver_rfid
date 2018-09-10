<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Ixudra\Curl\Facades\Curl;
use DB;
use Session;
use App\Shipment;

class QueueEntry extends Model
{
    protected $table = 'queue_entries';

    protected $connection = "sqlsrv";

    protected $dates = [
        'LocalTime',
    ];

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

    public function getKeyName(){
        return "CardholderID";
    }

    // Attributes Function
    public function setPlateNumberAttribute($value)
    {
        $this->attributes['plate_number'] = str_replace('_', '', $value);
    }

    // Relationships Model
    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class,'CardholderID','CardholderID')
            ->whereDate('change_date', Carbon::parse($this->created_at));
    }

    // public function getShipmentAttribute()
    // {
    //     return $this->shipment()->whereDate('created_at',Carbon::parse($this->created_at));
    // }

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

    //Seaerch Entry Queue
    public function qshipment()
    {
        return $this->belongsTo(Shipment::class,'CardholderID','CardholderID')
            ->whereDate('change_date', Session::get('queueDate'));
    }

    public function withinDayShipment()
    {
        return $this->belongsTo(Shipment::class,'CardholderID','CardholderID')
            ->whereDate('change_date', '>=', Session::get('queueDate'));
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

    // push to poll in queue entries to SAP
    public function scopePushNewQueue($query, $logId)
    {
        $log = 'LogID='.$logId;

        return $response = Curl::to('http://10.96.4.39/SapServiceTest/api/queues/push')
        ->withContentType('application/x-www-form-urlencoded')
        ->withData($log)
        ->post();

    }

    // Custom queue entries json cast
    public function toArray()
    {
        return [
            'id' => $this->id,
            'queue_number' => $this->queue_number,
            'driver_name' => $this->driver_name,
            'avatar' => $this->avatar,
            'plate_number'=> $this->plate_number,
            'hauler_name' => $this->hauler_name,
            'driverqueue_id' => $this->driverqueue_id,
            'shipment_number' => $this->shipment_number,
            'LogID' => $this->LogID,
            'CardholderID' => $this->CardholderID,
            'LocalTime' => $this->LocalTime,
            'isDRCompleted' => $this->isDRCompleted,
            'driver_availability' => $this->driver_availability,
            'truck_availability' => $this->truck_availability,
            'isTappedGateFirst' => $this->isTappedGateFirst,
            'isSecondDelivery' => $this->isSecondDelivery,
            'created_at' => $this->created_at,
            'truck' => $this->truck,
            'shipment' =>  $this->shipment,
            'lastCreated' => Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }

}
