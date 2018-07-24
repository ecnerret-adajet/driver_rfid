<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessGroup extends Model
{
    protected $connection = "sqlsrv_two";
    protected $table  = "AccessGroup";    
}
