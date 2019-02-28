<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Log;
use Carbon\Carbon;
use Ixudra\Curl\Facades\Curl;

class Shipment extends Model
{
    protected $connection = "sqlsrv";

    protected $fillable = [
        'LogID',
        'shipment_number',
        'change_date',
        'company_server',
        'CardholderID',
        'ControllerID',
        'DoorID',
        'status'
    ];

    protected $dates = ['change_date'];

    protected $hidden = [
        'updated_at',
    ];

    public function getDates()
    {
        return [];
    }

    public function getKeyName(){
        return "CardholderID";
    }

    // Model Relationships

    public function loading()
    {
        return $this->belongsTo(Loading::class,'shipment_number','shipment_number');
    }

    public function queueEntry()
    {
        return $this->belongsTo(QueueEntry::class,'LogID','LogID');
    }

    public function driver()
    {
        return $this->cardholder->driver();
    }

    public function cardholder()
    {
        return $this->belongsTo(Cardholder::class,'CardholderID','CardholderID');
    }

    public function log()
    {
        return $this->belongsTo(Log::class,'LogID','LogID');
    }

    public function scopeCheckIfShipped($query, $cardholder, $date)
    {
        $checkDate = !empty($date) ? Carbon::parse($date) : Carbon::today();

        // // Returns Cardholders from shipment where found in logs
        return $query->whereDate('created_at', $checkDate)
                    ->where('CardholderID',$cardholder)
                    ->orderBy('created_at','DESC')
                    ->pluck('shipment_number');
    }

    public function scopeCheckIfShippedDate($query, $cardholder, $date)
    {
        $checkDate = !empty($date) ? Carbon::parse($date) : Carbon::today();

        // // Returns Cardholders from shipment where found in logs
        return $query->whereDate('created_at', '=', $checkDate)
                    ->where('CardholderID',$cardholder)
                    ->orderBy('created_at','DESC')
                    ->pluck('shipment_number');
    }

    /**
     * Check if array cardholders has shipment assigned
     */
    public function scopeCheckIfShippedArray($query, $cardholder, $date)
    {
        $checkDate = !empty($date) ? Carbon::parse($date) : Carbon::today();

        // // Returns Cardholders from shipment where found in logs
        return $query->whereDate('created_at', $checkDate)
                    ->whereIn('CardholderID',$cardholder)
                    ->orderBy('created_at','DESC')
                    ->pluck('shipment_number');
    }

    public function scopeGetShipment($query, $logId)
    {
        $log = 'LogID='.$logId;

        $response = Curl::to('http://10.96.4.39/sapservice/api/assignedshipment')
        ->withContentType('application/x-www-form-urlencoded')
        ->withData($log)
        ->post();

        $ter = collect(json_decode($response, true))->pluck('shipment');
        $final = !empty($ter[0]) ? $ter[0] : '';

        return $final;
    }


    /**
     *  Get All Shipment Assigned for today
     *
     * @return Pluck
     *
     */
    public function scopeServedToday($query)
    {
        return $query->whereDate('created_at', Carbon::today())
                ->pluck('CardholderID');
    }

}
