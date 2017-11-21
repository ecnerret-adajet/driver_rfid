<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hauler extends Model
{
    protected $connection = "sqlsrv";
    protected $fillable = [
    	'name',
    	'address',
    	'contact_number',
        'availability',
        'vendor_number',
        'vendor_customer_code',
        'vat_reg_tin',
        'subconvendor_name',
        'subconvendor_number'
    ];

    protected $hidden = [
        'pivot'
    ];
    
    public function drivers()
    {
    	return $this->belongsToMany(Driver::class);
    }

    public function getDates()
    {
        return [];
    }


    // list all hauler with associated trucks
    public function trucks()
    {
        return $this->belongsToMany('App\Truck');
    }

    public function server()
    {
        return $this->belongsTo('App\Server');
    }

    /**
     *  List user where has a hauler_id assigned
     * 
     */
    public function user()
    {
        return $this->hasOne('App\User');
    }

}
