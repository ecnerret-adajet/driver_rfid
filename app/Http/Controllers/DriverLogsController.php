<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Driver;
use App\Log;
use App\Driverversion;
use App\Shipment;
use App\GateEntry;

class DriverLogsController extends Controller
{
    /**
     * Driver cardholders history
     */
    public function versions($driver_id) 
    {
        return Driverversion::where('driver_id',$driver_id)->orderBy('created_at','DESC');
    }

    /**
     * Driver History Logs
     */
    public function versionObject(Driver $driver)
    {
        return $this->versions($driver->id)->get();
    }

    /**
     * Display JSON driver logs
     */
    public function driverShipments(Driver $driver)
    {
       return Shipment::where('CardholderID',$driver->cardholder_id)
                        ->get()
                        ->unique('LogID')
                        ->values()
                        ->all();
    }

    public function driverLogs(Driver $driver)
    {
        // Unique Cardholder ID
        $cardholders = $this->versions($driver->id)->pluck('cardholder_id')->unique();

        // Unique Plate Number
        // $plateNumbers = $this->versions($driver->id)->pluck('plate_number')->unique();
        // Get All Versions Object
        // $versionsObject = $this->versions($driver->id)->get();

        // Array Merge Cardholder from version with the current cardholder assigned to driver
        $all_cardholder = array_collapse([$cardholders, $driver->cardholder_id]);

        $logs = Log::whereIn('CardholderID',[$driver->cardholder_id])
                ->orderBy('LocalTime','DESC')
                ->get();

        return $logs;
    }

    public function searchDriverLogs(Driver $driver, $date)
    {
        $checkDate = !empty($date) ? Carbon::parse($date) : Carbon::today();

        // Unique Cardholder ID
        $cardholders = $this->versions($driver->id)->pluck('cardholder_id')->unique();

        // Array Merge Cardholder from version with the current cardholder assigned to driver
        $all_cardholder = array_collapse([$cardholders, $driver->cardholder_id]);
        $checkCardholder = !empty($all_cardholder) ? $all_cardholder : $driver->cardholder_id;

        $logs = Log::whereIn('CardholderID', [$driver->cardholder_id])
                ->whereDate('LocalTime',$checkDate)
                ->orderBy('LocalTime','DESC')
                ->get();

        return $logs;

    }

}
