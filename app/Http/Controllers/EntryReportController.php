<?php

namespace App\Http\Controllers;

use App\Transformers\EntryReportTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use App\Transaction;
use App\Cardholder;
use App\QueueEntry;
use Carbon\Carbon;
use App\GateEntry;
use App\Shipment;
use Session;
use App\Log;
use Excel;
use DB;

class EntryReportController extends Controller
{
    /**
    * Export drivers logs to excel format
    *
    * @param App\Driverqueue $driverqueue_id
    * @param date $date
    * @return xslx
    */
    public function exportEntries($driverqueue_id, $date)
    {

        Session::put('date', Carbon::parse($date));
        $dateSearch = Session::get('date');

        $entries = GateEntry::with('queueEntry:CardholderID,LocalTime',
        'hasShipment:shipment_number,CardholderID,change_date,company_server',
        'hasShipment.loading',
        'hasTruckscaleIn:CardholderID,LocalTime',
        'hasTruckscaleOut:CardholderID,LocalTime',
        'hasGateOut:CardholderID,LocalTime')
        ->select('driver_name',
        'plate_number',
        'hauler_name',
        'LocalTime',
        'CardholderID')
        ->where('driverqueue_id',$driverqueue_id)
        ->whereBetween('LocalTime', [$dateSearch->format('Y-m-d 00:00:00'), $dateSearch->format('Y-m-d 23:59:00')])
        ->get()
        ->unique('driver_name');

        $uniqueEntires = $entries->values()->all();
        $entriesCount = $entries->count();


        Excel::create('driver_entries'.Carbon::now()->format('Ymdh'), function($excel) use ($uniqueEntires, $entriesCount, $dateSearch) {

            $excel->sheet('Sheet1', function($sheet) use ($uniqueEntires, $entriesCount, $dateSearch) {

                // Format the array JSON return
                $arr = array();
                foreach($uniqueEntires as $entry) {

                    $data = array(
                        'driver' => $entry->driver_name,

                        'plate' => $entry->plate_number,

                        'hauler' => $entry->hauler_name,

                        'gate_time_in' =>  date('Y-m-d h:i A', strtotime($entry->LocalTime)), // Driver pass

                        // return date for last DR
                        'last_dr_date' => Transaction::getLastDr($entry->plate_number,$dateSearch->format('Y-m-d'))->first(),

                        'queue_time' => !empty($entry->queueEntry->LocalTime) ? date('Y-m-d h:i A', strtotime($entry->queueEntry->LocalTime)) : null, // Queue

                        'shipment' => !empty($entry->hasShipment->change_date) ? date('Y-m-d h:i A', strtotime($entry->hasShipment->change_date)) : null,

                        'company' => !empty($entry->hasShipment->company_server) ? $entry->hasShipment->company_server : null,

                        // another gate time in for the truck entrer the plant with guard confirmation
                        'truck_gate_in' => empty($entry->hasShipment->change_date) ? null :
                        ( Log::truckGateIn($entry->CardholderID,$entry->hasShipment->change_date) == 'X' ? null : Log::truckGateIn($entry->CardholderID,$entry->hasShipment->change_date) ),

                        'sap_ts_in' => !empty($entry->hasShipment->loading->ts_in) ? date('Y-m-d h:i A', strtotime($entry->hasShipment->loading->ts_in)) : null,

                        'sap_ts_out' => !empty($entry->hasShipment->loading->ts_out) ? date('Y-m-d h:i A', strtotime($entry->hasShipment->loading->ts_out)) : null,

                        'sap_loading_start' => !empty($entry->hasShipment->loading->loading_start) ? date('Y-m-d h:i A', strtotime($entry->hasShipment->loading->loading_start)) : null,

                        'sap_loading_end' => !empty($entry->hasShipment->loading->loading_end) ? date('Y-m-d h:i A', strtotime($entry->hasShipment->loading->loading_end)) : null,

                        'ts_time_in' => !empty($entry->hasTruckscaleIn->LocalTime) ? date('Y-m-d h:i A', strtotime($entry->hasTruckscaleIn->LocalTime)) : null,

                        'ts_time_out' => !empty($entry->hasTruckscaleOut->LocalTime) ? date('Y-m-d h:i A', strtotime($entry->hasTruckscaleOut->LocalTime)) : null,

                        'gate_time_out' => !empty($entry->hasGateOut->LocalTime) ? date('Y-m-d h:i A', strtotime($entry->hasGateOut->LocalTime)) : null,
                    );

                    array_push($arr, $data);
                }


                //set the titles
                $sheet->fromArray($arr,null,'A1',false,false)
                ->setBorder('A1:P'.$entriesCount,'thin')
                ->prependRow(array(
                    'Driver Name',
                    'Plate Number',
                    'Hauler Name',
                    'Driver Pass Entry',
                    'Last DR Submitted',
                    'Queue Entry',
                    'Shipment date',
                    'Company',
                    'T_Entrance',
                    'SAP_TS_IN',
                    'SAP_TS_OUT',
                    'SAP_loading_start',
                    'SAP_loading_end',
                    'Truckscale In',
                    'Truckscale Out',
                    'Gate Out' ));


                    $sheet->cells('A1:P1', function($cells) {
                        $cells->setBackground('#f1c40f');
                    });

                });

            })->download('xlsx');
        }

        /**
        * Display driver logs as JSON from GateEntry model
        *
        * @param App\Driverqueue $driverqueue_id
        * @param Date $date
        * @return JSON
        */
        public function displayEntries($driverqueue_id, $date)
        {
            Session::put('date', Carbon::parse($date));
            $dateSearch = Session::get('date');

            $entries = GateEntry::with('queueEntry',
            'hasShipment',
            'hasShipment.loading',
            'hasTruckscaleIn',
            'hasTruckscaleOut',
            'hasGateOut')
            ->where('driverqueue_id',$driverqueue_id)
            ->whereBetween('LocalTime', [$dateSearch->format('Y-m-d 00:00:00'), $dateSearch->format('Y-m-d 23:59:00')])
            ->get()
            ->unique('driver_name');

            $uniqueEntires = $entries->values()->all();

            $manager = new Manager();
            $resource = new Collection($uniqueEntires, new EntryReportTransformer());

            return $manager->createData($resource)->toArray();

        }

        public function getLastDrSubmitted($plate_number)
        {
            $dateSearch = Session::get('date');

            return Transaction::getLastDr($plate_number, Carbon::now()->format('Y-m-d'))->first();
        }

        /**
        * Display the entries result
        *
        * @return view
        */
        public function viewDisplayEntries()
        {
            return view('entry-reports.report');
        }
    }
