<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    protected $connection = "dr_fp_database";
    protected $table = "transaction";

    protected $visible = [
        'plate_number',
        'shipment_number',
        'submission_date',
        'start_date',
        'do_status'
    ];

    public function scopeGetLastDr($query, $plate_number, $date)
    {
        $plate_format = trim(str_replace(['_','-'],' ', $plate_number));

        // $date_format = Carbon::parse($date);

        $first_filter = $query->where('plate_number',$plate_format)
                ->where('submission_date','<=',$date)
                ->orderBy('submission_date','DESC')
                ->take(1)
                ->pluck('submission_date');
                
        return $first_filter;
        
    }

    // public function toArray()
    // {
        // return [
        //     'plate_number' => $this->plate_number,
        //     'shipment_number' => $this->shipment_number,
        //     'submission_date' => $this->submission_date,
        //     'start_date' => $this->start_date,
        //     'do_status' => $this->do_status,
        // ];
    // }



}
