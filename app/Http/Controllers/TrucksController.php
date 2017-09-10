<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Truck;
use App\Driver;
use App\Hauler;
use App\Card;
use App\Capacity;
use Excel;
use App\Contract;
use Flashy;

class TrucksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('trucks.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // for testing
        // $rfid_card = Card::whereHas('binders', function($q) {
        //     $q->where('card_id','2');
        // });


        $haulers = ['' => ''] + Hauler::pluck('name','id')->all();

        $cards = ['' => ''] + Card::where('CardholderID',0)->pluck('CardNo','CardID')->all();

        $capacities = ['' => ''] + Capacity::pluck('description','id')->all();

        $contracts = ['' => ''] + Contract::pluck('contract_code','id')->all();

        $url = "http://10.96.4.39/trucking/rfc_get_vendor.php";
        $result = file_get_contents($url);
        $data = json_decode($result,true);

        $subvendors = collect($data)->where('vendor_number', '!=', '0000002000')->pluck('vendor_name','vendor_number');
        $vendors = collect($data)->pluck('vendor_name','vendor_number');


        return view('trucks.create', compact('haulers','cards','capacities','contracts','subvendors','vendors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $this->validate($request, [
            'plate_number' => 'required|max:8|min:8|unique:trucks',
            'card_list' => 'required',
            'capacity_list' => 'required',
            'contract_list' => 'required',
            'validity_start_date' => 'required',
            'vendor_description' => 'required',
        ],[
            'card_list.required' => 'RFID Number is required',
            'capacity_list.required' => 'Capacity Field is required',
            'contract_list.required' => 'Contract Code is required',
        ]);

        $card_rfid = $request->input('card_list');
        $capacity_id = $request->input('capacities_list');
        $truck = Truck::create($request->all());
        $truck->card()->associate($card_rfid);
        $truck->capacity()->associate($capacity_id);
        $truck->save();


        $truck->contracts()->attach($request->input('contract_list'));
        // $truck->haulers()->attach($request->input('hauler_list'));

        flashy()->success('Truck has successfully created!');
        return redirect('trucks');
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
    public function edit(Truck $truck)
    {
         $haulers = Hauler::pluck('name','id');
         $cards = ['' => ''] + Card::pluck('CardNo','CardID')->all();
         $capacities = Capacity::pluck('description','id');
        return view('trucks.edit', compact('truck','haulers','cards','capacities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Truck $truck)
    {

         $this->validate($request, [
            'plate_number' => 'required|max:8|min:8',
            'card_list' => 'required',
            'capacity_list' => 'required',
            'contract_list' => 'required',
            'validity_start_date' => 'required',
            'vendor_description' => 'required',
        ],[
            'card_list.required' => 'RFID Number is required',
            'capacity_list.required' => 'Capacity Field is required',
            'contract_list.required' => 'Contract Code is required',
        ]);

        $card_rfid = $request->input('card_list');
        $capacity_id = $request->input('capacities_list');
        $truck->update($request->all());
        $truck->card()->associate($card_rfid);
        $truck->capacity()->associate($capacity_id);
        $truck->save();

        flashy()->success('Truck has successfully updated!');
        return redirect('trucks');
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

    public function exportTrucks()
    {
        $trucks = Truck::all();
        
        Excel::create('trucks'.Carbon::now()->format('Ymdh'), function($excel) use ($trucks) {

            $excel->sheet('Sheet1', function($sheet) use ($trucks) {

                $arr = array();

                foreach($trucks as $truck) {
                    foreach($truck->drivers as $driver) {
                        foreach($driver->haulers as $hauler) {

                            $data =  array(
                            $truck->plate_number,
                            $truck->vehicle_type,
                            $truck->capacity,
                            $hauler->name,
                            $driver->name
                            );

                            array_push($arr, $data);

                        }
                    }
                }

                //set the titles
                $sheet->fromArray($arr,null,'A1',false,false)
                        ->setBorder('A1:E'.$trucks->count(),'thin')
                        ->prependRow(array(
                        'PLATE NUMBER', 'TRUCK TYPE', 'CAPACITY', 'HAULER', 'DRIVER NAME'));
                $sheet->cells('A1:E1', function($cells) {
                            $cells->setBackground('#f1c40f'); 
                });


            });

        })->download('xlsx');
        
    }
}
