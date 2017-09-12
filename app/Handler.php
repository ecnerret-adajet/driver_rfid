<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Handler extends Model
{
    protected $table = "handler_mapping";
    protected $fillable = [
        'vendor_number',
        'server_id'
    ];
}
