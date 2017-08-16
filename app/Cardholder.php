<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cardholder extends Model
{
    protected $connection = "sqlsrv_two";
    protected $table = "Cardholder";

     protected $fillable = [
    	'CardholderID'
    ];

    public function card()
    {
    	return $this->hasOne(Card::class,'CardholderID','CardholderID');
    }

    public function log()
    {
    	return $this->belongsTo('App\Log','CardholderID','CardholderID');
    }

    public function drivers()
    {
        return $this->hasMany('App\Driver','id','CardholderID');
    }

    public function pickups()
    {
        return $this->hasMany('App\Pickup','cardholder_id','CardholderID');
    }

    public function scopeMatched($query, $current)
    {
        return $query->whereHas('Log', function($q){
                    $q->where('CardholderID', '>=', 1)
                     ->where('LogID', '<=', $current)
                     ->where('LogID', '>=', $current-5);
                     })->pluck('Name');
    }
}
