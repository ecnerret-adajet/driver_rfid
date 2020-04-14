<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BookingRequest extends Model
{
    protected $fillable = [
        'order_reference',
        'order_reference_no',
        'consignee',
        'destination',
        'van_no',
        'ship_type',
        'mode_of_shipment',
        'booking_date',
        'shippers_name',
        'plate_number',
        'driver_name'
    ];

    public function getDates()
    {
        return [];
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
