<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Carbon\Carbon;

class Pickup extends Model
{

    use LogsActivity;

    // use \Venturecraft\Revisionable\RevisionableTrait;
    
    
    //     protected $revisionEnabled = true;
    //     protected $revisionCleanup = true;
    //     protected $historyLimit = 500;
    //     protected $revisionCreationsEnabled = true;
    
    // public static function boot()
    // {
    //     parent::boot();
    // }
    
    protected $connection = "sqlsrv";
    protected $fillable = [
        'plate_number',
        'company',
        'driver_name',
        'remarks',
        'deactivated_date',
        'do_number'
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
