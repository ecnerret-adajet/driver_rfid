<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Log;
use App\Driver;
use App\Truck;
use App\Driverqueue;
use App\Gate;
use App\Area;
use App\Cardholder;
use Flashy;
use Excel;
use DB;
use App\Shipment;

class MonitoringsController extends Controller
{
    /**
     * Store function for Gate
     */
    public function createGate()
    {
        $areas = Area::pluck('name','id');
        return view('monitorings.gateCreate',compact(
            'areas'
        ));
    }

    public function storeGate(Request $request)
    {
        $this->validate($request,[
            'area_list' => 'required',
            'title' => 'required|min:3',
            'door' => 'required|integer',
            'controller' => 'required|integer'
        ]);

        $gate = new Gate($request->all());
        $gate->user()->associate(Auth::user()->id);
        $gate->area()->associate($request->input('area_list'));
        $gate->save();

        flashy()->success('Gate has successfully created!');
        return redirect('gates/create');
    }

    /**
     *  Store function for queueing 
     */
    public function createQueue()
    {
        $gates = Gate::pluck('title','id');
        $areas = Area::pluck('name','id');
        return view('monitorings.queueCreate',compact(
            'gates',
            'areas'
        ));
    }

    public function storeQueue(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|min:3',
            'door' => 'required|integer',
            'controller' => 'required|integer',
            'ts_out_controller' => 'required|integer',
            'area_list' => 'required',
            'gate_list' => 'required'
        ]);

        $queue = new Driverqueue($request->all());
        $queue->user()->associate(Auth::user()->id);
        $queue->area()->associate($request->input('area_list'));
        $queue->gate()->associate($request->input('gate_list'));
        $queue->save();

        flashy()->success('Queue has successfully created!');
        return redirect('queues/create');
    }

    /**
     * Admin View Monitoring
     */
    public function queueEntries(Driverqueue $driverqueue)
    {
        // Get the total truckscale Out from truck monitoring today
        $check_truckscale_out = Log::truckscaleOutLocation($driverqueue->ts_out_controller);

        // Get the total drivers who tapped from Gate RFID
        $gateEntries =  Log::barrierLocation($driverqueue->gate->door,$driverqueue->gate->controller);
        
        // Get the queue result
        $result_lineups = Log::queueLocation($driverqueue->door, $driverqueue->controller, $check_truckscale_out, $gateEntries, Carbon::today());

        // Get the unique result from Cardholder
        $log_lineups = $result_lineups->unique('CardholderID');

        $arr = array();

        foreach($log_lineups as $key => $log) {
            foreach($log->drivers as $driver) {

                if(!empty($driver->truck->plate_number)) {
                    $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                    $z = str_replace('_','',$x);
                    $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                }

                $data = array(
                    'log_id' => substr($log->LogID, -4),
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' => empty($y) ? 'UNPROCESS' : $y, 
                    // 'driver_status' => $driver->availability,
                    'on_serving' => empty($driver->serves->where('created_at','>=',Carbon::today())->first()->on_serving) ? null : $driver->serves->first()->on_serving,

                );

                array_push($arr, $data);

            }
        }

        return $arr;
    }

    /**
     * Search Queues Entries By date
     */
    public function searchQueueEntries(Request $request, Driverqueue $driverqueue)
    {
        $this->validate($request, [
            'search_date' => 'required',
        ]);

        $search_date = $request->get('search_date');

        // Get the total truckscale Out from truck monitoring today
        $check_truckscale_out = Log::truckscaleOutLocationDate($driverqueue->ts_out_controller, $search_date);
        
        // Get the total drivers who tapped from Gate RFID
        $gateEntries =  Log::barrierLocationObject($driverqueue->gate->door,$driverqueue->gate->controller, $search_date)
                            ->pluck('CardholderID');

        // Get the queue result
        $result_lineups = Log::queueLocation($driverqueue->door, $driverqueue->controller, $check_truckscale_out, $gateEntries, $search_date);
        
        // Get the unique result from Cardholder
        $log_lineups = $result_lineups->unique('CardholderID');

        $arr = array();

        foreach($log_lineups as $key => $log) {
            foreach($log->drivers as $driver) {

                $data = array(
                    'log_id' => substr($log->LogID, -4),
                    'driver_id' => $driver->id,
                    'driver_avatar' => !empty($driver->image) ? $driver->image->avatar : $driver->avatar,
                    'driver_name' => $driver->name,
                    'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                    'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                    'log_time' => $log->LocalTime,
                    'dr_status' =>empty($driver->truck->plate_number) ? 'NO PLATE' : Truck::callLastTrip($driver->truck->plate_number),
                    // 'driver_status' => $driver->availability,
                    // 'on_serving' => empty($driver->serves->where('created_at','>=',Carbon::parse($search_date))->first()->on_serving) ? null : 1
                    'on_serving' =>  Shipment::checkIfShippedDate($log->CardholderID,$search_date)->first()


                );

                array_push($arr, $data);

            }
        }

        return $arr;
    }

    // Capture All RFIC Cards that has not a driver CARD
    public function barrierNoDriver()
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
    
     // check the direction of the barrier within the day
    public function getBarrierDirection($door, $cardholder, $direction)
    {
        // All Plant in 
        $barrier_in = Log::select('CardholderID','Direction','LocalTime')
        ->where('CardholderID',$cardholder)
        ->where('DoorID',$door)
        ->whereNotIn('CardholderID', $this->barrierNoDriver())
        ->where('CardholderID', '>=', 15)
        ->where('Direction', $direction)
        ->orderBy('LocalTime','DESC')
        ->first();

        if(empty($barrier_in)) {
            $x = null;
        } else {
            $x = $barrier_in->LocalTime;
        }

        return $x;
    }

    // check the direction of the barrier search
    public function getBarrierDirectionSearch($door, $cardholder, $direction, $date)
    {
        // All Plant in 
        $barrier_in = Log::select('CardholderID','Direction','LocalTime')
        ->where('CardholderID',$cardholder)
        ->whereDate('LocalTime',$date)
        ->where('DoorID',$door)
        ->whereNotIn('CardholderID', $this->barrierNoDriver())
        ->where('CardholderID', '>=', 15)
        ->where('Direction', $direction)
        ->orderBy('LocalTime','DESC')
        ->first();

        if(empty($barrier_in)) {
            $x = null;
        } else {
            $x = $barrier_in->LocalTime;
        }

        return $x;
    }


    public function gateEntries(Gate $gate)
    {
         // Get Logs from Bataan Barrier RFID
        $entries_count =  Log::barrierLocationObject($gate->door,$gate->controller, Carbon::today());

        // Format the array JSON return
        $arr = array();
        foreach($entries_count as $entry) {
            foreach($entry->drivers as $driver) {

                    $data = array(

                        'LogID' => $entry->LogID,
                        'CardholderID' => $entry->CardholderID,
                        'driver' => $driver->name,
                        'availability' => $driver->availability,
                        'avatar' => empty($driver->image) ?  $driver->avatar : $driver->image->avatar,
                        'plate_number' => empty($driver->truck->plate_number) ? 'NO DRIVER' : $driver->truck->plate_number,
                        'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                        'plate_availability' => empty($driver->truck->plate_number) ? null : $driver->truck->availability,
                        'hauler_name' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                        'inLocalTime' =>  $entry->LocalTime,
                        // 'inLocalTime' =>  $this->getBarrierDirection(0 ,$entry->CardholderID, 1),
                        // 'outLocalTime' =>  $this->getBarrierDirection(0, $entry->CardholderID, 2) < 
                        //                     $this->getBarrierDirection(0, $entry->CardholderID, 1) ? null : 
                        //                     $this->getBarrierDirection(0, $entry->CardholderID, 2),
                    );

                    array_push($arr, $data);
            }
        }

        return $arr;
    }

    /**
     * Search Gate RFID Entries
     */
    public function searchGateEntries(Request $request, Gate $gate)
    {
        $this->validate($request, [
            'search_date' => 'required',
        ]);

        $search_date = $request->get('search_date');

         // Get Logs from Bataan Barrier RFID
        $entries_count =  Log::barrierLocationObject($gate->door,$gate->controller, $search_date);

        // Format the array JSON return
        $arr = array();
        foreach($entries_count as $entry) {
            foreach($entry->drivers as $driver) {

                    $data = array(

                        'LogID' => $entry->LogID,
                        'CardholderID' => $entry->CardholderID,
                        'driver' => $driver->name,
                        'availability' => $driver->availability,
                        'avatar' => empty($driver->image) ?  $driver->avatar : $driver->image->avatar,
                        'plate_number' => empty($driver->truck->plate_number) ? 'NO DRIVER' : $driver->truck->plate_number,
                        'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                        'plate_availability' => empty($driver->truck->plate_number) ? null : $driver->truck->availability,
                        'hauler_name' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                        'inLocalTime' =>  $entry->LocalTime,
                        // 'outLocalTime' =>  $this->getBarrierDirectionSearch(0, $entry->CardholderID, 2, $search_date) < 
                        //                     $this->getBarrierDirectionSearch(0, $entry->CardholderID, 1, $search_date) ? null : 
                        //                     $this->getBarrierDirectionSearch(0, $entry->CardholderID, 2, $search_date),
                    );

                    array_push($arr, $data);
            }
        }

        return $arr;
    }

    /**
     * Show all RFID Gates
     */
    public function allGates()
    {
        $gates = Gate::all();
        
         return $gates;
    }

    /**
     * Show all RFID Queues
     */
    public function allQueues()
    {
        $queues = Driverqueue::all();

        return $queues;
    }

    /**
     * Export Queues
     */
    public function exportQueues(Driverqueue $driverqueue, $date)
    {
        // Get the total truckscale Out from truck monitoring today
        $check_truckscale_out = Log::truckscaleOutLocationDate($driverqueue->ts_out_controller, $date);

        // Get the total drivers who tapped from Gate RFID
        $gateEntries =  Log::barrierLocation($driverqueue->gate->door,$driverqueue->gate->controller);

        // Get the queue result
        $result_lineups = Log::queueLocation($driverqueue->door, $driverqueue->controller, $check_truckscale_out, $gateEntries, Carbon::parse($date));

        // Get the unique result from Cardholder
        $log_lineups = $result_lineups->unique('CardholderID');

        Excel::create('driver_queue'.Carbon::now()->format('Ymdh'), function($excel) use ($log_lineups) {

            $excel->sheet('Sheet1', function($sheet) use ($log_lineups) {

                $arr = array();

                foreach($log_lineups as $key => $log) {
                    foreach($log->drivers as $driver) {

                        if(!empty($driver->truck->plate_number)) {
                            $x = str_replace('-',' ',strtoupper($driver->truck->plate_number));
                            $z = str_replace('_','',$x);
                            $y = DB::connection('dr_fp_database')->select("CALL P_LAST_TRIP('$z','deploy')");
                        }

                        $data = array(
                            'log_id' => substr($log->LogID, -4),
                            'driver_name' => $driver->name,
                            'plate_number' => empty($driver->truck->plate_number) ? 'NO PLATE' : $driver->truck->plate_number,
                            'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                            'hauler' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                            'log_time' => $log->LocalTime,
                            // 'on_serving' => empty($driver->serves->where('created_at','>=',Carbon::today())->first()->on_serving) ? null : $driver->serves->first()->on_serving,

                        );

                        array_push($arr, $data);

                    }
                }

                //set the titles
                $sheet->fromArray($arr,null,'A1',false,false)
                        ->setBorder('A1:F'.$log_lineups->count(),'thin')
                        ->prependRow(array(
                        'LogID', 'Driver Name', 'Plate Number', 'Capacity', 'Hauler','Log Date'));
                $sheet->cells('A1:F1', function($cells) {
                            $cells->setBackground('#f1c40f'); 
                });

            });

        })->download('xlsx');    
    }

    /**
     * Export gate entries
     */
    public function exportGate(Gate $gate, $date)
    {
        // Get Logs from Bataan Barrier RFID
        $entries_count =  Log::barrierLocationObject($gate->door,$gate->controller, $date);

        Excel::create('gate_entries'.Carbon::now()->format('Ymdh'), function($excel) use ($entries_count) {

            $excel->sheet('Sheet1', function($sheet) use ($entries_count) {

                    // Format the array JSON return
                    $arr = array();
                    foreach($entries_count as $entry) {
                        foreach($entry->drivers as $driver) {

                                $data = array(
                                    'LogID' => $entry->LogID,
                                    'driver' => $driver->name,
                                    'plate_number' => empty($driver->truck->plate_number) ? 'NO DRIVER' : $driver->truck->plate_number,
                                    'capacity' =>  empty($driver->truck->capacity) ? null : $driver->truck->capacity->description, 
                                    'hauler_name' => empty($driver->hauler->name) ? 'NO HAULER' : $driver->hauler->name,
                                    'inLocalTime' =>  $entry->LocalTime,
                                );

                                array_push($arr, $data);
                        }
                    }

                //set the titles
                $sheet->fromArray($arr,null,'A1',false,false)
                        ->setBorder('A1:F'.$entries_count->count(),'thin')
                        ->prependRow(array(
                        'LogID', 'Driver Name', 'Plate Number', 'Capacity', 'Hauler','Log Date'));
                $sheet->cells('A1:F1', function($cells) {
                            $cells->setBackground('#f1c40f'); 
                });

            });

        })->download('xlsx');    
    }
}
