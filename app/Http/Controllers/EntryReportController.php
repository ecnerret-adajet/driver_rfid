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

class EntryReportController extends Controller
{
    public function exportEntries($driverqueue_id, $date)
    {
        $entries = GaateEntry::select('driver_name','plate_number','hauler_name','LocalTime')
                            ->with('queueEntry',
                            'hasTruckscaleIn',
                            'hasTruckscaleOut',
                            'hasGateOut')
                            ->where('driverqueue_id',$driverqueue_id)
                            ->whereDate('created_at',$date)
                            ->get();

        Excel::create('driver_entries'.Carbon::now()->format('Ymdh'), function($excel) use ($entries) {

            $excel->sheet('Sheet1', function($sheet) use ($entries) {

                //set the titles
                $sheet->fromArray($entries,null,'A1',false,false)
                        ->setBorder('A1:F'.$entries->count(),'thin')
                        ->prependRow(array(
                        'Driver Name', 'Plate Number', 'Hauler Name', 'Gate Entry', 'Queue Entry', 'Truckscale In', 'Truckscale Out', 'Gate Out' ));
                $sheet->cells('A1:H1', function($cells) {
                            $cells->setBackground('#f1c40f'); 
                });

            });

        })->download('xlsx'); 
                
    }
}