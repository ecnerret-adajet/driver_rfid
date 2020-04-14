<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\BookingRequest;

class BookRequestTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(BookingRequest $bookingRequest)
    {
        return [
            'id' => $bookingRequest->id,
            'order_reference' => $bookingRequest->order_reference,
            'order_reference_no' => $bookingRequest->order_reference_no,
            'booking_date' => (string) $bookingRequest->booking_date,
            'consignee' =>  $bookingRequest->consignee,
            'destination' =>  $bookingRequest->destination,
            'van_no' =>  $bookingRequest->van_no,
            'ship_type' =>  $bookingRequest->ship_type,
            'mode_of_shipment' =>  $bookingRequest->mode_of_shipment,
            'shippers_name' =>  $bookingRequest->shippers_name,
            'plate_number' =>  $bookingRequest->plate_number,
            'driver_name' =>  $bookingRequest->driver_name,
            'created_at' => (string)  $bookingRequest->created_at,
        ];
    }
}
