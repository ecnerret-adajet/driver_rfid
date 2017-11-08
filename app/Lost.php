<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Carbon\Carbon;

class Lost extends Model
{
    
    use LogsActivity;

    protected $fillable = [
        'reason'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function driver()
    {
        return $this->belongsTo('App\Driver','driver_id');
    }
}
