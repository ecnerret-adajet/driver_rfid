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
use DB;

class EntryReportController extends Controller
{
    public function exportEntries($driverqueue_id, $date)
    {
        $entries = GateEntry::with('queueEntry:CardholderID,LocalTime',
                            'hasShipment:CardholderID,change_date',
                            'hasTruckscaleIn:CardholderID,LocalTime',
                            'hasTruckscaleOut:CardholderID,LocalTime',
                            'hasGateOut:CardholderID,LocalTime')
                            ->select('driver_name',
                            'plate_number',
                            'hauler_name',
                            'LocalTime',
                            'CardholderID')
                            ->where('driverqueue_id',$driverqueue_id)
                            ->whereDate('created_at', Carbon::parse($date))
                            ->get()
                            ->unique('driver_name');

        $uniqueEntires = $entries->values()->all();
        $entriesCount = $entries->count();
         
        Excel::create('driver_entries'.Carbon::now()->format('Ymdh'), function($excel) use ($uniqueEntires, $entriesCount) {

            $excel->sheet('Sheet1', function($sheet) use ($uniqueEntires, $entriesCount) {

                    // Format the array JSON return
                    $arr = array();
                    foreach($uniqueEntires as $entry) {

                                $data = array(
                                    'driver' => $entry->driver_name,
                                    'plate' => $entry->plate_number,
                                    'hauler' => $entry->hauler_name,
                                    'gate_time_in' =>  date('Y-m-d h:i A', strtotime($entry->LocalTime)), 
                                    'queue_time' => !empty($entry->queueEntry->LocalTime) &&  $entry->queueEntry->LocalTime > $entry->Localtime  ? date('Y-m-d h:i A', strtotime($entry->queueEntry->LocalTime)) : null,
                                    'shipment' => !empty($entry->hasShipment->change_date) && $entry->hasShipment->change_date > $entry->Localtime ? date('Y-m-d h:i A', strtotime($entry->hasShipment->change_date)) : null,
                                    'ts_time_in' =>  !empty($entry->hasTruckscaleIn->LocalTime) && $entry->hasTruckscaleIn->LocalTime > $entry->LocalTime ? date('Y-m-d h:i A', strtotime($entry->hasTruckscaleIn->LocalTime)) : null,
                                    'ts_time_out' =>  !empty($entry->hasTruckscaleOut->LocalTime) && $entry->hasTruckscaleOut->LocalTime > $entry->LocalTime ? date('Y-m-d h:i A', strtotime($entry->hasTruckscaleOut->LocalTime)) : null,
                                    'gate_time_out' =>  !empty($entry->hasGateOut->LocalTime) ?  date('Y-m-d h:i A', strtotime($entry->hasGateOut->LocalTime)) : null,
                                );

                                array_push($arr, $data);
                    }


                //set the titles
                $sheet->fromArray($arr,null,'A1',false,false)
                        ->setBorder('A1:I'.$entriesCount,'thin')
                        ->prependRow(array(
                        'Driver Name', 
                        'Plate Number', 
                        'Hauler Name', 
                        'Gate Entry', 
                        'Queue Entry', 
                         'Shipment date', 
                        'Truckscale In', 
                        'Truckscale Out', 
                        'Gate Out' ));


                $sheet->cells('A1:I1', function($cells) {
                            $cells->setBackground('#f1c40f'); 
                });

            });

        })->download('xlsx'); 

        // return $entries->values()->all();
                
    }
}