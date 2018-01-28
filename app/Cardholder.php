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

    protected $hidden = [
        'FirstName',
        'LastName',
        'SendSMSMessage',
        'SMSMessage',
        'Gender',
        'Birthday',
        'EmployeeID',
        'Home_Phone',
        'Home_Fax',
        'MobilePhone',
        'Home_ZipCode',
        'Home_Address',
        'Home_EMail',
        'Home_HomePage',
        'Home_Notes',
        'JobTitle',
        'HireDate',
        'Department',
        'Office',
        'Office_Phone',
        'Office_Ext',
        'Office_Fax',
        'Office_CompanyName',
        'Office_ZipCode',
        'Office_Address',
        'Office_EMail',
        'Office_HomePage',
        'Office_Notes',
        'UserDefine01',
        'UserDefine02',
        'UserDefine03',
        'UserDefine04',
        'UserDefine05',
        'UserDefine06',
        'UserDefine07',
        'UserDefine08',
        'UserDefine09',
        'UserDefine10',
        'Photo',
        'AreaID',
        'AD_GUID',
        'Fingerprint01',
        'Fingerprint02',
        'Fingerprint03',
        'Fingerprint04',
        'Fingerprint05',
        'Fingerprint06',
        'Fingerprint07',
        'Fingerprint08',
        'Fingerprint09',
        'Fingerprint10',
        'AntiDuressID',
        'MiddleName',
        'DataGroupID',
        'Division',
        'FacialFeature',
        'FacialPrivilege',
        'SeparationDate',
        'ID',
    ];

    public function getDates()
    {
        return [];
    }

    public function cards()
    {
        return $this->hasMany(Card::class,'CardholderID','CardholderID');
    }

    public function logs()
    {
        return $this->hasMany('App\Log','CardholderID','CardholderID')
                    ->whereNotIn('ControllerID',[1]);
    }

    public function logsIn()
    {
        return $this->hasMany('App\Log','CardholderID','CardholderID')
                    ->where('Direction',1);
                 
    }

    public function logsOut()
    {
        return $this->hasMany('App\Log','CardholderID','CardholderID')
                    ->where('Direction',2);
    }

    public function oneDriver()
    {
        return $this->belongsTo('App\Driver','CardholderID','cardholder_id');
    }

    public function drivers()
    {
        return $this->hasMany('App\Driver','cardholder_id','CardholderID');
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

    /*
    *
    * Get pickup cards from cardholder 
    *
    */
    public function scopePickupCards($query)
    {
        $query->select('CardholderID')
              ->where('Name','LIKE','%Pickup%')
              ->get();
    }
}
