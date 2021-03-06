<?php

namespace App\Transformers;

use App\Log;
use App\GateEntry;
use Carbon\Carbon;
use App\Transaction;
use League\Fractal\TransformerAbstract;

class EntryReportTransformer extends TransformerAbstract
{
    /**
    * A Fractal transformer.
    *
    * @return array
    */
    public function transform(GateEntry $gateEntry)
    {

        return [
            'id' => $gateEntry->id,
            'avatar' => $gateEntry->avatar,
            'driver' => $gateEntry->driver_name,
            'plate' => $gateEntry->plate_number,
            'capacity' => $gateEntry->capacity['truck']['capacity']['description'],
            'hauler' => $gateEntry->hauler_name,
            'CardholderID' => $gateEntry->CardholderID,
            'driverpass' =>  $gateEntry->LocalTime,
            // 'last_dr_date' => Transaction::getLastDr($gateEntry->plate_number,$dateSearch->format('Y-m-d'))->first(),
            'last_dr_date' => !empty($gateEntry->queueEntry) ? $gateEntry->queueEntry->isDRCompleted : null,
            'queue_time' => !empty($gateEntry->queueEntry->LocalTime) ? $gateEntry->queueEntry->LocalTime : null, // Queue
            'shipment' => !empty($gateEntry->hasShipment->change_date) ? $gateEntry->hasShipment->change_date : null,
            'company' => !empty($gateEntry->hasShipment->company_server) ? $gateEntry->hasShipment->company_server : null,
            // another gate time in for the truck entrer the plant with guard confirmation
            // 'truck_gate_in' => empty($gateEntry->hasShipment->change_date) ? null :
            // ( Log::truckGateIn($gateEntry->CardholderID,$gateEntry->hasShipment->change_date) == 'X' ? null : Log::truckGateIn($gateEntry->CardholderID,$gateEntry->hasShipment->change_date) ),

            'truck_plant_in' => !empty($gateEntry->hasShipment->change_date) ? $gateEntry->hasPlantIn($gateEntry->CardholderID, $gateEntry->hasShipment->change_date) : null,

            'sap_ts_in' => !empty($gateEntry->hasShipment->loading->ts_in) ? date('Y-m-d H:i:s.u', strtotime($gateEntry->hasShipment->loading->ts_in)) : null,
            'sap_ts_out' => !empty($gateEntry->hasShipment->loading->ts_out) ? date('Y-m-d H:i:s.u', strtotime($gateEntry->hasShipment->loading->ts_out)) : null,
            'sap_loading_start' => !empty($gateEntry->hasShipment->loading->loading_start) ? date('Y-m-d H:i:s.u', strtotime($gateEntry->hasShipment->loading->loading_start)) : null,
            'sap_loading_end' => !empty($gateEntry->hasShipment->loading->loading_end) ? date('Y-m-d H:i:s.u', strtotime($gateEntry->hasShipment->loading->loading_end)) : null,
            'ts_time_in' => !empty($gateEntry->hasTruckscaleIn->LocalTime) ? date('Y-m-d H:i:s.u', strtotime($gateEntry->hasTruckscaleIn->LocalTime)) : null,
            'ts_time_out' => !empty($gateEntry->hasTruckscaleOut->LocalTime) ? date('Y-m-d H:i:s.u', strtotime($gateEntry->hasTruckscaleOut->LocalTime)) : null,
            'gate_time_out' => !empty($gateEntry->hasGateOut->LocalTime) ? date('Y-m-d H:i:s.u', strtotime($gateEntry->hasGateOut->LocalTime)) : null,

        ];
    }
}
