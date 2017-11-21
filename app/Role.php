<?php 

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    public function permissions()
    {
        return $this->belongsToMany('App\Permission');
    }

    public function getDates()
    {
        return [];
    }
}