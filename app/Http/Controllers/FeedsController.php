<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Log;
use Carbon\Carbon;
use JavaScript;
use App\Cardholder;
use App\Customer;
use App\Hauler;
use App\Truck;

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

    public function notDriver()
    {
        $pickup_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%pickup%')
        ->pluck('CardholderID'); 

        $guard_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%GUARD%')
        ->pluck('CardholderID'); 

        $executive_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%EXECUTIVE%')
        ->pluck('CardholderID'); 

        // Remove all cardholder without driver assigned
        $not_driver = array_collapse([$pickup_cards, $guard_cards, $executive_cards]);

        return $not_driver;
    }

    public function generateHomeFeed(Request $request)
    {
        $this->validate($request, [
            'search_date' => 'required',
        ]);

        $search_date = $request->get('search_date');

        $queues = Log::select('CardholderID','LocalTime')
            ->where('ControllerID', 1)
            ->where('DoorID',0)
            ->whereDate('LocalTime', Carbon::parse($search_date))
            ->orderBy('LogID','ASC')
            ->get();

        $barrier_in = Log::select('LogID','CardholderID')
            ->whereIn('DoorID',[3])
            ->whereNotIn('CardholderID',$this->notDriver())
            ->whereDate('LocalTime', Carbon::parse($search_date))
            ->where('CardholderID', '>=', 15)
            ->orderBy('LocalTime','DESC')
            ->get();

        $all_in = Log::where('CardholderID', '>=', 1)
            ->where('Direction', 1)
            ->whereDate('LocalTime', Carbon::parse($search_date))
            ->orderBy('LocalTime','DESC')->get();


        $all_out = Log::where('CardholderID', '>=', 1)
            ->where('Direction', 2)
            ->whereDate('LocalTime', Carbon::parse($search_date))
            ->orderBy('LocalTime','DESC')->get();

        $logs = Log::with('drivers','drivers.hauler','drivers.truck','customers') // customer removed
        ->whereNotIn('ControllerID',[1])
        ->whereNotIn('CardholderID',$this->notDriver())
        ->whereNotIn('DoorID',[3])
        ->where('CardholderID', '>=', 1)
        ->whereDate('LocalTime', '>=', Carbon::parse($search_date))
        ->orderBy('LocalTime','DESC')
        ->get();

        $today_result = $logs->unique('CardholderID');
        
        $arr = array();

        foreach($today_result as $result) {
            if(count($result->drivers) != 0) {
                foreach($result->drivers as $driver) {

                    $sticker_in = !empty($all_in->where('CardholderID', $result->CardholderID)->first()) ? Log::match($all_in->where('CardholderID', $result->CardholderID)->first()->LogID)->pluck('CardholderID','CardholderID') : null;
                    $sticker_out = !empty($all_out->where('CardholderID', $result->CardholderID)->first()) ? Log::match($all_out->where('CardholderID', $result->CardholderID)->first()->LogID)->pluck('CardholderID','CardholderID') : null;

                    $data = array(
                        'id' => $result->LogID,
                        'avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                        'driver_name' => $driver->name,
                        'plate_number' => !empty($driver->truck) ? $driver->truck->first()->plate_number : null,
                        'hauler' => !empty($driver->hauler) ? $driver->hauler->first()->name : null,
                        'plant_in' => !empty($barrier_in->where('CardholderID', $result->CardholderID)->first()) ? $barrier_in->where('CardholderID', $result->CardholderID)->first()->LocalTime : null,
                        'on_queue' => !empty($queues->where('CardholderID', $result->CardholderID)->first()) ? $queues->where('CardholderID', $result->CardholderID)->first()->localtime : null,
                        'truckscale_in' => !empty($all_in->where('CardholderID', $result->CardholderID)->first()) ? $all_in->where('CardholderID', $result->CardholderID)->first()->LocalTime : null,
                        'truckscale_in_id' => !empty($all_in->where('CardholderID', $result->CardholderID)->first()) ? $all_in->where('CardholderID', $result->CardholderID)->first()->LogID : null,
                        'truckscale_out' => !empty($all_out->where('CardholderID', $result->CardholderID)->first()) ? $all_out->where('CardholderID', $result->CardholderID)->first()->LocalTime : null,
                        'truckscale_out_id' => !empty($all_out->where('CardholderID', $result->CardholderID)->first()) ? $all_out->where('CardholderID', $result->CardholderID)->first()->LogID : null,
                        'sticker_in' => !empty(array_has($sticker_in, $result->CardholderID)) ? true : null,
                        'sticker_out' => !empty(array_has($sticker_out, $result->CardholderID)) ? true : null,
                    );

                    array_push($arr, $data);

                }                 
            }                 
        }        

        return $arr;
    }

    public function homeFeed() 
    {
        $queues = Log::select('CardholderID','LocalTime')
            ->where('ControllerID', 1)
            ->where('DoorID',0)
            ->whereDate('LocalTime', Carbon::now())
            ->orderBy('LogID','ASC')
            ->get();

        $barrier_in = Log::select('LogID','CardholderID')
            ->whereIn('DoorID',[3])
            ->whereNotIn('CardholderID',$this->notDriver())
            ->whereDate('LocalTime', Carbon::now())
            ->where('CardholderID', '>=', 15)
            ->orderBy('LocalTime','DESC')
            ->get();

        $all_in = Log::where('CardholderID', '>=', 1)
            ->where('Direction', 1)
            ->whereDate('LocalTime', Carbon::now())
            ->orderBy('LocalTime','DESC')->get();


        $all_out = Log::where('CardholderID', '>=', 1)
            ->where('Direction', 2)
            ->whereDate('LocalTime', Carbon::now())
            ->orderBy('LocalTime','DESC')->get();

        $logs = Log::with('drivers','drivers.hauler','drivers.truck','customers') // customer removed
        ->whereNotIn('ControllerID',[1])
        ->whereNotIn('CardholderID',$this->notDriver())
        ->whereNotIn('DoorID',[3])
        ->where('CardholderID', '>=', 1)
        ->whereDate('LocalTime', '>=', Carbon::now())
        ->orderBy('LocalTime','DESC')
        ->get();

        $today_result = $logs->unique('CardholderID');
        
        $arr = array();

        foreach($today_result as $result) {
            if(count($result->drivers) != 0) {
                foreach($result->drivers as $driver) {

                    $sticker_in = !empty($all_in->where('CardholderID', $result->CardholderID)->first()) ? Log::match($all_in->where('CardholderID', $result->CardholderID)->first()->LogID)->pluck('CardholderID','CardholderID') : null;
                    $sticker_out = !empty($all_out->where('CardholderID', $result->CardholderID)->first()) ? Log::match($all_out->where('CardholderID', $result->CardholderID)->first()->LogID)->pluck('CardholderID','CardholderID') : null;

                    $data = array(
                        'id' => $result->LogID,
                        'avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                        'driver_name' => $driver->name,
                        'plate_number' => !empty($driver->truck) ? $driver->truck->first()->plate_number : null,
                        'hauler' => !empty($driver->hauler) ? $driver->hauler->first()->name : null,
                        'plant_in' => !empty($barrier_in->where('CardholderID', $result->CardholderID)->first()) ? $barrier_in->where('CardholderID', $result->CardholderID)->first()->LocalTime : null,
                        'on_queue' => !empty($queues->where('CardholderID', $result->CardholderID)->first()) ? $queues->where('CardholderID', $result->CardholderID)->first()->localtime : null,
                        'truckscale_in' => !empty($all_in->where('CardholderID', $result->CardholderID)->first()) ? $all_in->where('CardholderID', $result->CardholderID)->first()->LocalTime : null,
                        'truckscale_in_id' => !empty($all_in->where('CardholderID', $result->CardholderID)->first()) ? $all_in->where('CardholderID', $result->CardholderID)->first()->LogID : null,
                        'truckscale_out' => !empty($all_out->where('CardholderID', $result->CardholderID)->first()) ? $all_out->where('CardholderID', $result->CardholderID)->first()->LocalTime : null,
                        'truckscale_out_id' => !empty($all_out->where('CardholderID', $result->CardholderID)->first()) ? $all_out->where('CardholderID', $result->CardholderID)->first()->LogID : null,
                        'sticker_in' => !empty(array_has($sticker_in, $result->CardholderID)) ? true : null,
                        'sticker_out' => !empty(array_has($sticker_out, $result->CardholderID)) ? true : null,
                    );

                    array_push($arr, $data);

                }                 
            }                 
        }        

        return $arr;
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

    public function getTotalTrucksInPlant()
    {
             // Check Trucks Who Has Truckcscale Out
        $check_truckscale_out = Log::select('CardholderID')
                                    ->where('ControllerID', 4)
                                    ->where('Direction',2)
                                    ->whereDate('LocalTime', Carbon::now())
                                    ->whereBetween('LocalTime', [Carbon::now()->subDays(3), Carbon::now()])
                                    ->pluck('CardholderID');

        // Check Trucks who has Truckscale in but not out
        $check_truckscale_in = Log::select('CardholderID')
                                    ->where('ControllerID', 4)
                                    ->where('Direction',1)
                                    ->whereNotIn('CardholderID',$check_truckscale_out)
                                    ->whereBetween('LocalTime', [Carbon::now()->subDays(3), Carbon::now()])
                                    ->pluck('CardholderID')
                                    ->count();

        return $check_truckscale_in;
    }

}
