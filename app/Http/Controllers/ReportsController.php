<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Collection;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Log;
use App\Driver;
use App\Truck;
use App\Hauler;
use DB;
use Excel;
use App\Monitor;

class ReportsController extends Controller
{
    public function entries()
    {
        $haulers = Hauler::pluck('name','id');
        $count_days = 0;

        $today_result = Log::enties()->unique('CardholderID');

        return view('reports.entries', compact(
            'haulers',
            'count_days',
            'today_result'));
    }

    public function generateEntries(Request $request)
    {
        $this->validate($request, [
			'start_date' => 'required',
			'end_date' => 'required',
			'hauler_list' => 'required',
        ]);
        
        $start_date = $request->get('start_date');
		$end_date = $request->get('end_date');
		$hauler_list = $request->input('hauler_list');
        $count_days = Carbon::parse($start_date)->diffInDays(Carbon::parse($end_date));
        
        $logs = Log::where('CardholderID', '>=', 1)
		->whereBetween('LocalTime', [Carbon::parse($start_date), Carbon::parse($end_date)])
	    ->orderBy('LocalTime','ASC')
	    ->with(['drivers.haulers' => function($q) use ($hauler_list){
	   		$q->whereIn('id', $hauler_list);
        }])->get();
        
        $today_result = $logs->unique('CardholderID');
        
        $haulers = Hauler::pluck('name','id');

        $trips = Log::whereDate('LocalTime', '>=' ,$start_date)
        ->whereDate('LocalTime', '<=', $end_date)
        ->orderBy('LocalTime','ASC')
        ->get();

        $between = ( Carbon::parse($start_date)->diffInDays(Carbon::parse($end_date)) == 0 ? 1 : Carbon::parse($start_date)->diffInDays(Carbon::parse($end_date))  );
	    $col_count = $between + 1;
	    $index =0;
        
        if($count_days <= 7){
            
            return view('reports.entries', 
            compact('start_date',
                    'end_date',
                    'count_days',
                    'hauler_list',
                    'value',
                    'index',
                    'between',
                    'col_count',
                    'trips',
                    'logs',
                    'haulers',
                    'boom',
                    'today_result'));

        } else {

            return redirect('entries')->with('status', 'Please select a date range not more than 7 days, retry again');
        
        }

    }

    public function generateEntriesExport()
	{
		session_start();
		$start_date =  $_SESSION["start_date"];
		$end_date = $_SESSION["end_date"];
		$hauler_list = $_SESSION["hauler_list"]; 
		$count_days = Carbon::parse($start_date)->diffInDays(Carbon::parse($end_date));

		$logs = Log::where('CardholderID', '>=', 1)
		->whereBetween('LocalTime', [Carbon::parse($start_date), Carbon::parse($end_date)])
		->orderBy('LocalTime','ASC')
		->with(['drivers.haulers' => function($q) use ($hauler_list){
			$q->whereIn('id', $hauler_list);
		}])->get();

		$today_result = $logs->unique('CardholderID');

		// adding header dates
		$top_header = array();
		if(!empty($start_date) && !empty($end_date)) {
			for ($x = $start_date; $x <= $end_date; $x=date('Y-m-d', strtotime($x. ' + 1 days'))) {
				$top_header[] = date('F d', strtotime($x));
			}
		}

		// adding result arrays to table
			$result_array = array();
			if(!empty($start_date) && !empty($end_date)) {
				for ($x = $start_date; $x <= $end_date; $x=date('Y-m-d', strtotime($x. ' + 1 days'))) {

				$result_array[] = $x;
					
				}
			}

		// Export Excel File
		Excel::create('trucking_report'.Carbon::now()->format('Ymdh'), function($excel) use ($today_result, $top_header, $result_array, $logs) {

		$excel->sheet('Sheet1', function($sheet) use ($today_result, $top_header, $result_array, $logs) {

			$sheet->loadView('reports.export_entries')
				->with('today_result',$today_result)
				->with('top_header',$top_header)
				->with('result_array',$result_array)
				->with('logs',$logs);

			});

		})->download('xlsx');
	}
}
