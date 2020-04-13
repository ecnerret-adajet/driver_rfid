<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BookingRequest;

class BookRequestController extends Controller
{
    public function index()
    {
        return view('bookingRequest.index');
    }

    public function show($bookingRequest)
    {
        return view('bookingRequest.show',compact('bookingRequest'));
    }
}
