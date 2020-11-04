<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Pickup extends Model
{

    use LogsActivity;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $connection = "sqlsrv";
    protected $fillable = [
        'plate_number',
        'company',
        'driver_name',
        'remarks',
        'deactivated_date',
        'activation_date',
        'do_number',
        'coa',
        'bypass_rfid',
        'helper',
        'pickup_date'
    ];


    public function getDates()
    {
        return [];
    }


    protected static $logAttributes = [
        'plate_number',
        'company',
        'driver_name',
        'remarks',
        'availability'
    ];

    /**
        * driver model has a user authenticated belongsto relationship
        */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function cardholder()
    {
        return $this->belongsTo('App\Cardholder','cardholder_id','CardholderID');
    }

    public function log()
    {
        return $this->belongsTo('App\Log','cardholder_id','CardholderID');
    }

}
