<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vehicle;

class VehicleApiController extends Controller
{
    public function checkGpsStatus($plate_number)
    {
        $filteredPlate = str_replace('-',' ',$plate_number);

        $vehicle =  Vehicle::with('gpsdevice')->where('plate_number',$filteredPlate)->first();

        if($vehicle) {
            if($vehicle->gpsdevice) {
                return response()->json(['result' => "gps-enabled"], 200);
            } else {
                return response()->json(['result' => []], 204);
            }
        }
            return response()->json(['result' => []], 204);

    }
}
