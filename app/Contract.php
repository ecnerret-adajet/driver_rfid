<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    public function getContractAttribute() 
    {
       return $this->contract_code . ' - ' . $this->contract_description;
    }
}
