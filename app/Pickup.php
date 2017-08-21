<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pickup extends Model
{

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
        'remarks'
    ];     

    /**
        * driver model has a user authenticated belongsto relationship
        */
    public function user()
    {
        return $this->belongsTo(User::class);
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
