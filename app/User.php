<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, EntrustUserTrait, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone_number','company', 'bypass_rfid','two_factor_code','two_factor_expires_at','lfug_customer_code','pfmc_customer_code'
    ];

    protected static $logAttributes = [
        'name',
        'email'
    ];

    protected $casts = [
        'bypass_rfid'
    ];

    public function getDates()
    {
        return [];
    }

    protected $dates = [
        'two_factor_expires_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','created_at','updated_at'
    ];

    public function userLocation()
    {
        return $this->belongsTo(UserLocation::class);
    }

    public function generateTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = Carbon::now()->addMinutes(10);
        $this->save();
    }

    public function resetTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    /**
     * driver relationship
     *
     * @return void
     */
    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    /**
     * get role
     */
    public function getRoleListAttribute()
    {
        return $this->roles->pluck('id')->all();
    }


    public function confirms()
    {
        return $this->hasMany(Confirm::class);
    }

        /**
    *
    *get the associated user from monitor database
    *
    */
    public function monitors(){
        return $this->hasMany('App\Monitor');
    }

    /*
    *
    * Get all associate user from queue model
    *
    */
    public function lineups()
    {
        return $this->hasMany('App\Lineup');
    }

    /**
    *
    * Get the associated user from pickup created
    *
    */
    public function pickups()
    {
        return $this->hasMany('App\Pickup');
    }


    /**
     *
     *  Driver's Pass Accepted By
     *
     */
    public function passes()
    {
        return $this->hasMany(Pass::class);
    }

    /**
     * User can create a truck
     */
    public function trucks()
    {
        return $this->hasMany('App\Truck');
    }

    /**
     *   Assocaite User who request a RFID re-printing
     *
     */
    public function losts()
    {
        return $this->hasMany('App\Lost');
    }

    /**
     * Assigned Hauler where a user assigned
     */
    public function hauler()
    {
        return $this->belongsTo('App\Hauler');
    }

    /**
     * For deliveries serving queues
     */
    public function serves()
    {
        return $this->hasMany(Serve::class);
    }

    /**
     * record the user's company
     */
    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    /**
     * Get SPC inspector who deactivated / activate an truck
     */
    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    /**
     * Linked to a user who created a queue monitoring
     */
    public function driverqueues()
    {
        return $this->hasMany(Driverqueue::class);
        // return $this->hasMany(Queue::class);
    }

    /**
     * Linked to a user who created a gate RFID
     */
    public function gates()
    {
        return $this->hasMany(Gate::class);
    }

    /**
     * Linked to a user who created a replacement card
     *
     * @return void
     */
    public function replacements()
    {
        return $this->hasMany(Replacement::class);
    }


}
