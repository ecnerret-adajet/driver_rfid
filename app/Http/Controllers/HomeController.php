<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Driver;
use App\Hauler;
use App\Truck;
use App\Card;
use App\User;

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
        $prints = Driver::where('print_status',1)->count();
        $haulers = Hauler::all()->count();
        $trucks = Truck::all()->count();
        $drivers = Driver::all()->count();
        $cards = Card::all()->count();
        $users = User::all()->count();

        $data = array(
            'prints' => $prints,
            'haulers' => $haulers,
            'trucks' => $trucks,
            'drivers' => $drivers,
            'cards' => $cards,
            'users' => $users,
        );

        return $data;
    }
}
