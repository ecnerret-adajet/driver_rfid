<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Log;
use Carbon\Carbon;
use JavaScript;
use App\Cardholder;
use App\Customer;

class FeedsController extends Controller
{

    public function imgRfid($cardholder, $logid)
    {
        return 'http://172.17.2.25:8080/RFID/'.date('Ymd',strtotime($cardholder)).'/AC.'.date('Ymd',strtotime($cardholder)).'.0000'.$logid.'-1.jpg';
    }

    public function index() 
    {
        return view('feed');
    }

    public function googleMap($address)
    {
        $destination = str_replace(' ','+',$address);
        //make request
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=L2-3+B1+BV+Romero+Blvd,+Tondo,+Manila,+Tondo,+Manila,+Metro+Manila&destination=".$destination."&key=AIzaSyDc28EA8qpYrsF10DKWKa4CSVKYSNZrudQ";
        $map_result = file_get_contents($url);
        $map_json = json_decode($map_result,true);

        return $map_json;
    }

    public function homeFeed()
    {
        $loading = 1;

        $pickup_cards = Cardholder::select('CardholderID')
        ->where('Name', 'LIKE', '%Pickup%')
        ->get();

        $logs = Log::whereNotIn('ControllerID',[1])
        ->whereNotIn('CardholderID',$pickup_cards)
        ->whereNotIn('DoorID',[3])
        ->where('CardholderID', '>=', 1)
        ->whereDate('LocalTime', '>=', Carbon::now())
        ->orderBy('LocalTime','DESC')->get();


        $all_out = Log::where('CardholderID', '>=', 1)
                ->where('Direction', 2)
                ->whereDate('LocalTime', Carbon::now())
                ->orderBy('LocalTime','DESC')->get();

        $all_in = Log::where('CardholderID', '>=', 1)
                ->where('Direction', 1)
                ->whereBetween('LocalTime', [Carbon::now()->subDays(1), Carbon::now()])
                ->orderBy('LocalTime','DESC')->get();

        $all_in_2 = Log::where('CardholderID', '>=', 1)
        ->where('Direction', 1)
        ->whereDate('LocalTime', Carbon::now())
        ->orderBy('LocalTime','DESC')->get();


        $today_result = $logs->unique('CardholderID');

         // return logs from barrier
         $barriers = Log::whereIn('DoorID',[3])
         ->whereNotIn('CardholderID',$pickup_cards)
         ->where('CardholderID', '>=', 15)
         ->whereDate('LocalTime', '>=', Carbon::now())
         ->orderBy('LocalTime','DESC')->get();
 
         $barrier_in = Log::where('DoorID',3)
         ->where('CardholderID', '>=', 15)
         ->where('Direction', 1)
         ->whereDate('LocalTime', Carbon::now())
         ->orderBy('LocalTime','DESC')->get();
 
         $barrier_out = Log::where('DoorID',3)
         ->where('CardholderID', '>=', 15)
         ->where('Direction', 2)
         ->whereDate('LocalTime', Carbon::now())
         ->orderBy('LocalTime','DESC')->get();

        return view('home_content', compact('logs','today_result','barrier_in','barrier_out','barriers',
		'all_out','all_in','all_in_2','loading'));

    }

    public function feedContent()
    {

        $pickup_cards = Cardholder::select('CardholderID')
        ->where('Name', 'LIKE', '%Pickup%')
        ->get();

        $logs = Log::whereNotIn('ControllerID',[1])
        ->whereNotIn('CardholderID',$pickup_cards)
        ->where('CardholderID', '>=', 1)
        ->whereDate('LocalTime', '>=', Carbon::now())
        ->orderBy('LocalTime','DESC')->get();


        $all_out = Log::where('CardholderID', '>=', 1)
                ->where('Direction', 2)
                ->whereDate('LocalTime', Carbon::now())
                ->orderBy('LocalTime','DESC')->get();

        $all_in = Log::where('CardholderID', '>=', 1)
                ->where('Direction', 1)
                ->whereBetween('LocalTime', [Carbon::now()->subDays(1), Carbon::now()])
                ->orderBy('LocalTime','DESC')->get();

        $all_in_2 = Log::where('CardholderID', '>=', 1)
        ->where('Direction', 1)
        ->whereDate('LocalTime', Carbon::now())
        ->orderBy('LocalTime','DESC')->get();

        $today_result = $logs->unique('CardholderID');

        return view('feed_content', compact('logs','today_result',
		'all_out','all_in','all_in_2'));
    }

    public function barrier() 
    {
        return view('barrier');
    }

    public function barrierContent()
    {

        $pickup_cards = Cardholder::select('CardholderID')
        ->where('Name', 'LIKE', '%Pickup%')
        ->get();

         // return logs from barrier
         $barriers = Log::whereIn('DoorID',[3])
         ->whereNotIn('CardholderID',$pickup_cards)
         ->where('CardholderID', '>=', 15)
         ->whereDate('LocalTime', '>=', Carbon::now())
         ->orderBy('LocalTime','DESC')->get();
 
         $barrier_in = Log::where('DoorID',3)
         ->where('CardholderID', '>=', 15)
         ->where('Direction', 1)
         ->whereDate('LocalTime', Carbon::now())
         ->orderBy('LocalTime','DESC')->get();
 
         $barrier_out = Log::where('DoorID',3)
         ->where('CardholderID', '>=', 15)
         ->where('Direction', 2)
         ->whereDate('LocalTime', Carbon::now())
         ->orderBy('LocalTime','DESC')->get();

         $barrier_results = $barriers->unique('CardholderID');

         return view('barrier_content',compact('barriers','barrier_in','barrier_out','barrier_results'));

    }
}
