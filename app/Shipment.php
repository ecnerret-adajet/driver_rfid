<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Log;
use Carbon\Carbon;

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

    public function queueEntry()
    {
        return $this->belongsTo(QueueEntry::class,'CardholderID','CardholderID');
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
