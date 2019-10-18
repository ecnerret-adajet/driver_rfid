<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\QueueEntry;

class QueueProcessApiController extends Controller
{
    public function priority()
    {
        // Make queue a priorty 
        // queue will not be pushed to front
    }

    public function enqueue()
    {
        // adding queue to array list
    }

    public function dequeue()
    {
        // removing a qeueu when shipment was perform
    }

    public function front()
    {
        // shows the item queue to be serve
    }

    public function rear()
    {
        // shows the last entry of the queue
    }
}
