<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dequeue;

class DequeueController extends Controller
{
    public function create()
    {
        return view('dequeue.create');
    }
}
