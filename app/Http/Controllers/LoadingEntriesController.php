<?php

namespace App\Http\Controllers;

use App\Transformers\LoadingEntriesTransformer;
use App\Transformers\QueueEntriesDashTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Traits\SapApiTrait;
use App\Traits\NotDriverTrait;
use App\Traits\QueueTrait;
use App\Events\QueueEntryEvent;
use App\Driverqueue;
use App\LoadingEntry;
use App\GateEntry;
use App\Truck;
use App\Driver;
use App\Log;
use App\Cardholder;
use Carbon\Carbon;
use App\Shipment;
use Auth;
use DB;
use Session;

class LoadingEntriesController extends Controller
{
    use NotDriverTrait, QueueTrait, SapApiTrait;

    public function __construct()
    {
        $this->notDriver();
    }

    public function getPicklistData($shipment_number)
    {
        $sapConnection = [
            'ashost' => '172.17.2.37',
            'sysnr' => '00',
            'client' => '100',
            'user' => 'payproject',
            'passwd' => 'welcome69+',
        ];

        $commodities = $this->executeSapFunction($sapConnection, 'ZFM_PICKING_LIST', [
            'SHIPMENT' => $shipment_number,
        ], null);

        if ($commodities) {
            return response()->json(['data' => $commodities], 200);
        } else {
            return response()->json(['data' => 'no shipment'], 201);
        }
    }

    // Show all recently queue
    public function getLoadingEntries($driverqueue_id)
    {
        $checkTruckscaleOut = collect(Log::truckscaleOutFromQueue($driverqueue_id))->unique();

        $queues = LoadingEntry::where('driverqueue_id', $driverqueue_id)
            ->whereNotIn('CardholderID', $checkTruckscaleOut->values()->all())
            ->where('created_at', '>=', Carbon::today())
            // ->whereNull('shipment_number')
            ->doesntHave('shipment')
            ->whereNotNull('driver_availability')
            ->whereNotNull('truck_availability')
            ->where('isDRCompleted', 'NOT LIKE', '%0000-00-00%')
            ->whereNotNull('isTappedGateFirst')
            ->orderBy('LocalTime', 'ASC')
            ->get()
            ->unique('CardholderID');

        return $queues->values()->all();
    }

    // Store new queue entry
    public function storeLoadingEntries(Request $request, $driverqueue_id)
    {

        $driverLocation = Driverqueue::where('id', $driverqueue_id)->first();

        $lastLogEntry = Log::where('DoorID', $driverLocation->door)
            ->where('ControllerID', $driverLocation->controller)
            ->whereNotIn('CardholderID', $this->notDriver())
            ->where('CardholderID', '>=', 15)
            ->orderBy('LocalTime', 'DESC')
            ->with('driver', 'driver.image', 'driver.truck', 'driver.hauler')
            ->first();

        $loadingEntry = LoadingEntry::firstOrCreate(
            [
                'LogID' => $lastLogEntry->LogID,
            ],
            [
                'shipment_number' => Shipment::checkIfShipped($lastLogEntry->CardholderID, null)->first(),
                'driver_name' => $lastLogEntry->driver->name,
                'plate_number' => !empty($lastLogEntry->driver->truck) ? $lastLogEntry->driver->truck->plate_number : null,
                'hauler_name' => !empty($lastLogEntry->driver->hauler) ? $lastLogEntry->driver->hauler->name : null,
                'CardholderID' => $lastLogEntry->CardholderID,
                'loading_number' => $this->checkIfExist($driverLocation->id),
                'isDRCompleted' =>  !empty($lastLogEntry->driver->truck) ? Truck::callLastTrip($lastLogEntry->driver->truck->plate_number) : null,
                'isTappedGateFirst' => !empty(GateEntry::checkIfTappedFromGate($lastLogEntry->CardholderID)) ? 1 : null,
                'isSecondDelivery' => $this->checkIfReturned($lastLogEntry->CardholderID) > 0 ? 1 : 0,
                'driver_availability' => !empty($lastLogEntry->driver) && $lastLogEntry->driver->availability == 1 ? 1 : null,
                'truck_availability' => !empty($lastLogEntry->driver->truck) && $lastLogEntry->driver->truck->availability == 1 ? 1 : null,
                'isShipmentStarted' => 0,
                'truck_id' => !empty($lastLogEntry->driver->truck) ? $lastLogEntry->driver->truck->id : null,
                'avatar' => !empty($lastLogEntry->driver->image) ? $lastLogEntry->driver->image->avatar : $lastLogEntry->driver->avatar,
                'driverqueue_id' => $driverLocation->id,
                'LocalTime' => $lastLogEntry->LocalTime,
            ]
        );

        if ($loadingEntry->wasRecentlyCreated == true) {

            if (!$this->notDriver()) {
                LoadingEntry::pushNewQueue($loadingEntry->LogID);
            }

            event(new QueueEntryEvent($loadingEntry, $driverLocation));
            return $loadingEntry;
        } else {
            $queueLast = LoadingEntry::where('driverqueue_id', $driverLocation->id)->orderBy('id', 'DESC')->first();
            return $queueLast;
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
