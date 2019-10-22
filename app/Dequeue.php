<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dequeue extends Model
{
    protected $fillable = [
        'remarks',
        'isApproved'
    ];

    public function queueEntry()
    {
        return $this->belongsTo(QueueEntry::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function confirmBy()
    {
        return $this->belongsTo(User::class);
    }
}
