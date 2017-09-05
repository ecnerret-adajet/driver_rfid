<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Log;
use Carbon\Carbon;
use JavaScript;
use App\Cardholder;

class FeedsController extends Controller
{

    public function index() 
    {
        return view('feed');
    }

    public function homeFeed()
    {
        $loading = 1;

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

        return view('home_content', compact('logs','today_result',
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
}
