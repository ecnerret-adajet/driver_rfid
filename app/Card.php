<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Card extends Model
{

    use LogsActivity;

    protected $connection = "sqlsrv_two";
    protected $table = "Card";
    public $timestamps = false;

    protected $hidden = [
        'CodeType',
        'Deactivation',
        'PinCode',
        'Privilege',
        'AreaID',
        'FingerprintExist',
        'DataGroupID',
        'DisableLockCard',
        'CardNoIntCode',
        'ActivationDate',
        'DeactivationDate',
        'AccessGroupID',
        'SeparationDate',
    ];

    public function getDates()
    {
        return [];
    }

    protected static $logAttributes = [
        'binders', 
    ];

    public function getKeyName(){
        return "CardID";
    }

    public function getFullDeployAttribute()
    {   
        return $this->CardNo .' - '. $this->cardholder['Name'];
    }

    public function cardholder()
    {
    	return $this->belongsTo(Cardholder::class,'CardholderID','CardholderID');
    }

    public function binders() 
    {
        return $this->hasMany('App\Binder','card_id','CardID');
    }

    public function drivers()
    {
        return $this->hasMany('App\Driver','card_id');
    }

    public function truck()
    {
        return $this->hasOne('App\Truck','card_id','CardID');
    }

}
