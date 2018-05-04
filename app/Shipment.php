<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Log;
use Carbon\Carbon;

class Shipment extends Model
{
    protected $unguard = [
        '*'
    ];

    public function getDates()
    {
        return [];
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
        return $query->whereDate('created_at', '=', $checkDate)
                    ->where('CardholderID',$cardholder)
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
