<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerDoNumber extends Model
{
    protected $connection = "sqlsrv";

    protected $fillable = [
    	'pickup_id',
        'do_number',
        'customer_code'
    ];
}
