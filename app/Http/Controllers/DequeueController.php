<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dequeue;
use App\Transformers\QueueEntriesTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use App\QueueEntry;

class DequeueController extends Controller
{

    public function index()
    {
        return view('dequeue.index');
    }

    public function create($queue_id)
    {   
        $queueEntry = QueueEntry::whereId($queue_id)->first();

        // return $queueEntry;

        return view('dequeue.create', compact('queueEntry'));
    }
}
