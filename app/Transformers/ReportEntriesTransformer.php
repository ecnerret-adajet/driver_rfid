<?php

namespace App\Transformers;

use App\Log;
use App\GateEntry;
use Carbon\Carbon;
use App\Transaction;
use App\Traits\DateTimeDiffTrait;
use League\Fractal\TransformerAbstract;

class ReportEntriesTransformer extends TransformerAbstract
{
    use DateTimeDiffTrait;
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(GateEntry $gateEntry)
    {
        return [
            'driver' => $gateEntry->driver_name,
            'plate' => $gateEntry->plate_number,
            'hauler' => $gateEntry->hauler_name,
            'driverpass' =>  date('Y-m-d h:i A', strtotime($gateEntry->LocalTime)),
            'last_dr_date' => !empty($gateEntry->queueEntry) ? $gateEntry->queueEntry->isDRCompleted : "N/A",
            'queue_time' => !empty($gateEntry->queueEntry->LocalTime) ? date('Y-m-d h:i A', strtotime($gateEntry->queueEntry->LocalTime)) : "N/A", // Queue
            'shipment' => !empty($gateEntry->hasShipment->change_date) ? date('Y-m-d h:i A', strtotime($gateEntry->hasShipment->change_date)) : "N/A",
            'company' => !empty($gateEntry->hasShipment->company_server) ? $gateEntry->hasShipment->company_server : "N/A",
            'truck_plant_in' => !empty($gateEntry->hasShipment->change_date) ? $gateEntry->hasPlantIn($gateEntry->CardholderID, $gateEntry->hasShipment->change_date) : "N/A",
            'ts_time_in' => !empty($gateEntry->hasTruckscaleIn->LocalTime) ? date('Y-m-d h:i A', strtotime($gateEntry->hasTruckscaleIn->LocalTime)) : "N/A",
            'ts_time_out' => !empty($gateEntry->hasTruckscaleOut->LocalTime) ? date('Y-m-d h:i A', strtotime($gateEntry->hasTruckscaleOut->LocalTime)) : "N/A",
            'gate_time_out' => !empty($gateEntry->hasGateOut->LocalTime) ? date('Y-m-d h:i A', strtotime($gateEntry->hasGateOut->LocalTime)) : "N/A",
            'sap_ts_in' => !empty($gateEntry->hasShipment->loading->ts_in) ? date('Y-m-d h:i A', strtotime($gateEntry->hasShipment->loading->ts_in)) : "N/A",
            'sap_ts_out' => !empty($gateEntry->hasShipment->loading->ts_out) ? date('Y-m-d h:i A', strtotime($gateEntry->hasShipment->loading->ts_out)) : "N/A",
            'sap_loading_start' => !empty($gateEntry->hasShipment->loading->loading_start) ? date('Y-m-d h:i A', strtotime($gateEntry->hasShipment->loading->loading_start)) : "N/A",
            'sap_loading_end' => !empty($gateEntry->hasShipment->loading->loading_end) ? date('Y-m-d h:i A', strtotime($gateEntry->hasShipment->loading->loading_end)) : "N/A",
            'driverpass_to_queue' => !empty($gateEntry->queueEntry->LocalTime) && !empty($gateEntry->LocalTime) ? $this->DateTimeDiff(date('Y-m-d H:i', strtotime($gateEntry->queueEntry->LocalTime)),date('Y-m-d H:i', strtotime($gateEntry->LocalTime))) : "N/A",
            'queue_to_shipment' => !empty($gateEntry->hasShipment->change_date) && !empty($gateEntry->queueEntry->LocalTime) ? $this->DateTimeDiff(date('Y-m-d H:i', strtotime($gateEntry->hasShipment->change_date)),date('Y-m-d H:i', strtotime($gateEntry->queueEntry->LocalTime))) : "N/A",
            'shipment_to_plant_in' => !empty($gateEntry->hasShipment->change_date) && $gateEntry->hasPlantIn($gateEntry->CardholderID, $gateEntry->hasShipment->change_date) != 'N/A' ? $this->DateTimeDiff(date('Y-m-d H:i', strtotime($gateEntry->hasShipment->change_date)),$gateEntry->hasPlantInReport($gateEntry->CardholderID, $gateEntry->hasShipment->change_date)) : "N/A",
            // 'plant_in_to_ts_in' => !empty($gateEntry->hasTruckscaleIn->LocalTime) &&  $gateEntry->hasPlantIn($gateEntry->CardholderID, $gateEntry->hasShipment->change_date) != 'N/A' ? $this->DateTimeDiff(date('Y-m-d H:i', strtotime($gateEntry->hasTruckscaleIn->LocalTime)),$gateEntry->hasPlantInReport($gateEntry->CardholderID, $gateEntry->hasShipment->change_date)) : "N/A",
            'ts_in_to_sap_loading_start' => !empty($gateEntry->hasTruckscaleIn->LocalTime) && !empty($gateEntry->hasShipment->loading->loading_start) ?  $this->DateTimeDiff(date('Y-m-d H:i', strtotime($gateEntry->hasTruckscaleIn->LocalTime)),date('Y-m-d H:i', strtotime($gateEntry->hasShipment->loading->loading_start))) : "N/A",
            'sap_loading_start_to_sap_loading_end' => !empty($gateEntry->hasShipment->loading->loading_start) && !empty($gateEntry->hasShipment->loading->loading_end) ? $this->DateTimeDiff(date('Y-m-d H:i', strtotime($gateEntry->hasShipment->loading->loading_start)),date('Y-m-d H:i', strtotime($gateEntry->hasShipment->loading->loading_end))) : "N/A",
            'sap_loading_end_to_ts_out' => !empty($gateEntry->hasShipment->loading->loading_end) &&  !empty($gateEntry->hasTruckscaleOut->LocalTime) ? $this->DateTimeDiff(date('Y-m-d H:i', strtotime($gateEntry->hasShipment->loading->loading_end)),date('Y-m-d H:i', strtotime($gateEntry->hasTruckscaleOut->LocalTime))) : "N/A",
            'ts_out_to_plant_out' => !empty($gateEntry->hasTruckscaleOut->LocalTime) && !empty($gateEntry->hasGateOut->LocalTime) ? $this->DateTimeDiff(date('Y-m-d H:i', strtotime($gateEntry->hasTruckscaleOut->LocalTime)),date('Y-m-d H:i', strtotime($gateEntry->hasGateOut->LocalTime))) : "N/A",
        ];
    }
}
