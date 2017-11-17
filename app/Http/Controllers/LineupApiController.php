<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lineup;
use App\Cardholder;
use App\Card;
use Carbon\Carbon;
use App\Log;
use DB;

class LineupApiController extends Controller
{
   public function getDriverQue()
   {
        $result_lineups = Log::with(['drivers','drivers.trucks','drivers.haulers'])
        ->where('ControllerID',1)
        ->where('DoorID',0)
        ->where('CardholderID', '>=', 15)
        ->where('LocalTime', '>=', Carbon::now()->subDay())
        ->orderBy('LogID','DESC')->get();

        $log_lineups = $result_lineups->unique('CardholderID');

        return $log_lineups;
   }

   public function checkSubmissionDate($plate_number)
   {
       $x = str_replace('-',' ',$plate_number);
       $last_trip = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$x','deploy')");
       
       if(!empty($last_trip)) {
           foreach($last_trip as $trip) {
               $submission = $trip->submission_date;                
           }
       } else {
           $submission = 'UNPROCESS';
       }

       return $submission;
   }

}
