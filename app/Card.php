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
    ];

    protected static $logAttributes = [
        'binders', 
    ];

    public function getKeyName(){
        return "CardID";
    }

    public function getCardHolderAttribute()
    {   
        return $this->CardNo .' - '. $this->getCardholder->Name;
    }

    public function getCardholder()
    {
        return $this->belongsTo(Cardholder::class,'CardholderID','CardholderID')->select('Name');
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
        return $this->hasOne('App\Truck','CardID','card_id');
    }

}
