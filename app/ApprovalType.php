<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalType extends Model
{
    protected $connection = "sqlsrv";

    protected $fillable = [
        'name'
    ];
}
