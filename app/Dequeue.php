<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dequeue extends Model
{
    protected $fillable = [
        'remarks',
        'isApproved'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function getDates()
    {
        return [];
    }

    protected $hidden = [
        'created_at',
        'updated_at'
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
