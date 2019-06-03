<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Replacement extends Model
{
    protected $fillable = [
        'reason_replacement',
        'remarks'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
