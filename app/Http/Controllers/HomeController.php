<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Driver;
use App\Hauler;
use App\Truck;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home', compact('drivers','hauler'));
    }

    public function homeStatus()
    {
        $print = Driver::where('print_status',1)->count();
        $hauler = Hauler::all()->count();
        $truck = Truck::all()->count();
        $driver = Driver::all()->count();

        $data = array(
            'print' => $print,
            'hauler' => $hauler,
            'truck' => $truck,
            'driver' => $driver
        );

        return $data;
    }
}
