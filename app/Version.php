<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $connection = "sqlsrv";
    protected $fillable = [
        'plate_number',
        'reg_number',
        'vendor_number',
        'subvendor_number',
        'contract_code',
        'contract_description',
        'vendor_description',
        'subvendor_description',
        'start_validity_date',
        'end_validity_date',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function drivers()
    {
        return $this->belongsToMany(Driver::class);
    }

    public function trucks()
    {
        return $this->belongsToMany(Truck::class);
    }

    public function getTruckListAttribute()
    {
        return $this->trucks()->pluck('id')->all();
    }

}
