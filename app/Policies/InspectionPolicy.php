<?php

namespace App\Policies;

use App\User;
use App\Truck;
use Illuminate\Auth\Access\HandlesAuthorization;

class InspectionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if truck is for activation state
     * 
     * @param \App\Truck $truck
     * 
     */

     public function activate(User $user, Truck $truck) 
     {
         return $truck->access_location != 0 or $truck->availability == 0;
     }

     /**
      * Determine if truck is for deactivate state
      * 
      *@param \App\Truck $truck
      * 
      */
      public function deactivate(User $user, Truck $truck)
      {
          return $truck->access_location == 0 && $truck->availability == 1;
      }
    
}
