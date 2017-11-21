<?php 

namespace App;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{

    public function roles()  
    {
        return $this->belongsToMany('App\Role');
    }

    public function getDates()
    {
        return [];
    }

}