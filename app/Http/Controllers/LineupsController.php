<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ConfirmLineup;
use App\Lineup;
use App\Log;
use Flashy;
use Carbon\Carbon;
use App\Driver;
use App\Setting;
use App\User;
use DB;

class LineupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $result_lineups = Log::with(['drivers','drivers.trucks','drivers.haulers'])
        ->where('ControllerID',1)
        ->where('DoorID',0)
        ->where('CardholderID', '>=', 15)
        ->where('LocalTime', '>=', Carbon::now())
        ->orderBy('LogID','DESC')->get();

        $log_lineups = $result_lineups->unique('CardholderID');

        $lineups = Lineup::all();
        
        return view('lineups.index', compact('log_lineups','lineups'));
    }

    /**
     * Check truck last trip in DR Fastpay
     *
     * 
     */
    public function checkLastTrip($plate_number)
    {
        $x = str_replace('-',' ',$plate_number);
        $last_trip = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$x','deploy')");
        
        if(!empty($last_trip)) {
            foreach($last_trip as $trip) {
                $last_trip_plate_number = $trip->do_status;                
            }
        } else {
            $last_trip_plate_number = 'NOPE';
        }

        return $last_trip_plate_number;
    }

    /**
    *
    *
    * Generater Record form lineup model database
    *
    */
    public function generateLineups(Request $request)
    {
        $this->validate($request,[
            'start_date' => 'required|before:end_date',
            'end_date' => 'required'
        ]);

        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $lineups = Lineup::all();

        $result_lineups = Log::with(['drivers','drivers.trucks','drivers.haulers'])
        ->where('ControllerID',1)
        ->where('DoorID',0)
        ->where('CardholderID', '>=', 15)
        ->whereBetween('LocalTime', [Carbon::parse($start_date), Carbon::parse($end_date)])
        ->orderBy('LogID','DESC')->get();

        $log_lineups = $result_lineups->unique('CardholderID');

        return view('lineups.index', compact('log_lineups','lineups'));
    }

    public function lineupJson()
    {
        $drivers = Driver::select('cardholder_id')->get();

        $result_lineups = Log::with(['drivers','drivers.trucks','drivers.haulers'])
        ->whereIn('CardholderID',$drivers)
        ->where('ControllerID',1)
        ->where('DoorID',0)
        ->where('CardholderID', '>=', 15)
        ->where('LocalTime', '>=', Carbon::now())
        ->orderBy('LogID','DESC')->get();

        $log_lineups = $result_lineups->unique('CardholderID');

        return $log_lineups;
    }

    public function markedJson()
    {
        $marked = Lineup::whereDate('created_at',Carbon::now())->count(); 
        return $marked;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function create($log)
     {
         return view('lineups.create',compact('log'));
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function store(Request $request, $log)
     {
         $lineup = new Lineup;
         $lineup->user_id = Auth::user()->id;
         $lineup->LogID = $log;
         $lineup->availability = 0;
         $lineup->approval_notif = 1;
         $lineup->save();
 
         //send email to supervisor for hustling
         $setting = Setting::with('user')->where('id',3)->first();
         Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmLineup($lineup));
 
         flashy()->warning('This action will take effect once the approve');
         return redirect('lineups');
     }

     public function hustlingApproval($id)
     {
         $lineup = Lineup::findOrFail($id);
         return view('lineups.approval', compact('lineup'));
     }

    /**
     * 
     *  Queue item to hustling trucks
     *
     */
     public function hustlingApprovalStore(Request $request, $id)
     {
        $lineup = Lineup::findOrFail($id);

        $lineup->fill($request->all());

        if($request->input('status') == '0') {  

            $lineup->hustling = 1;
            $lineup->approval_notif = 0;

        } else {

            $lineup->hustling = 0;
            $lineup->approval_notif = 1;

        }
        
        $lineup->save();
        return redirect('lineups');
     }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
