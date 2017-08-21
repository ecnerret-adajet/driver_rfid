<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Log;
use App\Cardholder;
use App\Customer;
use App\Hauler;
use App\Driver;
use App\Truck;
use App\User;
use DB;
use Carbon\Carbon;

class LogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    *
    * Displays all Trucking Logs
    *
    */
    public function index()
    {
        $pickups = Cardholder::pickupCards();
        $logs = Log::with(['drivers',
                           'drivers.transfers',
                           'drivers.trucks',
                           'customers',])->logsNoPickup($pickups);

        $today_log = $logs->unique('CardholderID')->take(35); //35

        // return view('home', compact(
        //     'all_in',
        //     'all_out',
        //     'today_log'));
        
        return $today_log;
    }

    public function entriesIn()
    {
        $all_in = Log::fullEntriesIn();
        return $all_in;
    }

    public function entriesOut()
    {
        $all_out = Log::fullEntriesOut();
        return $all_out;
    }

    public function googleMap($address, $text)
    {
        $destination = str_replace(' ','+',$address);
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=L2-3+B1+BV+Romero+Blvd,+Tondo,+Manila,+Tondo,+Manila,+Metro+Manila&destination=$destination&key=AIzaSyDc28EA8qpYrsF10DKWKa4CSVKYSNZrudQ";
        $result = file_get_contents($url);
        $data = json_decode($result,true);

        if($data['status'] != "NOT_FOUND" && $data['status'] != "ZERO_RESULTS")
        {
            return $data['routes'][0]['legs'][0][$text]['text'];
        } else {
            return 'CANNOT DETERMINE';
        }
    }
}
