<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\QueueEntry;
use App\GateEntry;
use App\Shipment;
use App\Log;
use App\Cardholder;
use Excel;
use Session;
use DB;

class EntryReportController extends Controller
{
    public function exportEntries($driverqueue_id, $date)
    {

        Session::put('date', Carbon::parse($date));
        $dateSearch = Session::get('date');

        $entries = GateEntry::with('queueEntry:CardholderID,LocalTime',
                            'hasShipment:CardholderID,change_date,company_server',
                            'hasTruckscaleIn:CardholderID,LocalTime',
                            'hasTruckscaleOut:CardholderID,LocalTime',
                            'hasGateOut:CardholderID,LocalTime')
                            ->select('driver_name',
                            'plate_number',
                            'hauler_name',
                            'LocalTime',
                            'CardholderID')
                            ->where('driverqueue_id',$driverqueue_id)
                            ->whereDate('created_at', $dateSearch)
                            ->get()
                            ->unique('driver_name');

        $uniqueEntires = $entries->values()->all();
        $entriesCount = $entries->count();
         
        Excel::create('driver_entries'.Carbon::now()->format('Ymdh'), function($excel) use ($uniqueEntires, $entriesCount, $date) {

            $excel->sheet('Sheet1', function($sheet) use ($uniqueEntires, $entriesCount, $date) {

                    // Format the array JSON return
                    $arr = array();
                    foreach($uniqueEntires as $entry) {

                                $data = array(
                                    'driver' => $entry->driver_name,
                                    
                                    'plate' => $entry->plate_number,
                                    
                                    'hauler' => $entry->hauler_name,

                                    'gate_time_in' =>  date('Y-m-d h:i A', strtotime($entry->LocalTime)), 

                                    'queue_time' => !empty($entry->queueEntry->LocalTime) ? date('Y-m-d h:i A', strtotime($entry->queueEntry->LocalTime)) : null,
                                    
                                    'shipment' => !empty($entry->hasShipment->change_date) ? date('Y-m-d h:i A', strtotime($entry->hasShipment->change_date)) : null,

                                    'company' => !empty($entry->hasShipment->company_server) ? $entry->hasShipment->company_server : null,
                                    
                                    'ts_time_in' => !empty($entry->hasTruckscaleIn->LocalTime) ? date('Y-m-d h:i A', strtotime($entry->hasTruckscaleIn->LocalTime)) : null,
                                    
                                    'ts_time_out' => !empty($entry->hasTruckscaleOut->LocalTime) ? date('Y-m-d h:i A', strtotime($entry->hasTruckscaleOut->LocalTime)) : null,
                                    
                                    'gate_time_out' => !empty($entry->hasGateOut->LocalTime) ? date('Y-m-d h:i A', strtotime($entry->hasGateOut->LocalTime)) : null,
                                );

                                array_push($arr, $data);
                    }


                //set the titles
                $sheet->fromArray($arr,null,'A1',false,false)
                        ->setBorder('A1:J'.$entriesCount,'thin')
                        ->prependRow(array(
                        'Driver Name', 
                        'Plate Number', 
                        'Hauler Name', 
                        'Gate Entry', 
                        'Queue Entry', 
                        'Shipment date', 
                        'Company',
                        'Truckscale In', 
                        'Truckscale Out', 
                        'Gate Out' ));


                $sheet->cells('A1:J1', function($cells) {
                            $cells->setBackground('#f1c40f'); 
                });

            });

        })->download('xlsx'); 

       
                
    }
}