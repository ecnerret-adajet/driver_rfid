<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone_number'
    ];

    protected static $logAttributes = [
        'name', 
        'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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


}
