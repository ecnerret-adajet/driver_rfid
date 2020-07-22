<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SapDoNumber extends Model
{
    protected $connection = "sqlsrv";

    protected $fillable = [
    	'sap_date',
        'do_number',
        'customer_code',
        'status',
        'server'
    ];
}
