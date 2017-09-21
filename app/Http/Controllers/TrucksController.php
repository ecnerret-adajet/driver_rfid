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
use App\Version;
use App\Base;
use App\Plant;
use App\Cardholder;

class TrucksController extends Controller
{

    public function vendorSubvendor()
    {
        $url = "http://10.96.4.39/trucking/rfc_get_vendor.php?server=lfug";
        $result = file_get_contents($url);
        $data = json_decode($result,true);

        return $data;
    }


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

        $haulers = ['' => ''] + Hauler::pluck('name','name')->all();

        $haulers_subcon = ['' => ''] + Hauler::where('vendor_number', '!=', '0000002000')->pluck('name','id')->all();
        

        $cards = Card::orderBy('CardNo','DESC')
                ->where('CardholderID','>=', 15)
                ->where('CardholderID','!=', 0)->pluck('CardNo','CardID');

                // $cards = Card::all()
                // ->where('CardholderID','>=', 15)
                // ->where('CardholderID','!=', 0)
                // ->pluck('card_holder','CardID');

        $capacities = Capacity::pluck('description','id');

        $contracts = ['' => ''] + Contract::all()->pluck('contract','id')->all();

        $bases = Base::pluck('origin','id');

        $plants = Plant::pluck('plant_name','id');

        $subvendors = ['NO SUBVENDOR' => 'NO SUBVENDOR'] + collect($this->vendorSubvendor())->where('vendor_number', '!=', '0000002000')->pluck('vendor_name','vendor_number')->all();
        $vendors = collect($this->vendorSubvendor())->pluck('vendor_name','vendor_number');

        return view('trucks.create', compact('haulers','cards','capacities','contracts','subvendors','vendors','bases','plants','haulers_subcon'));
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
            'plate_number' => 'required_without:reg_number|max:8|unique:trucks',
            'card_list' => 'required',
            'capacity_list' => 'required',
            'contract_list' => 'required',
            'validity_start_date' => 'required',
            'hauler_list' => 'required',
            'vendor_description' => 'required',
            'base_list' => 'required',
            'plant_list' => 'required',
            'validity_start_date' => 'required|before:validity_end_date',
            'validity_end_date' => 'required',
        ],[
            'card_list.required' => 'RFID Number is required',
            'capacity_list.required' => 'Capacity Field is required',
            'contract_list.required' => 'Contract Code is required',
            'hauler_list.required' => 'Vendor Name is required'
        ]);

        $card_rfid = $request->input('card_list');
        $capacity_id = $request->input('capacity_list');
        $base_id = $request->input('base_list');
        // $plant_id = $request->input('plant_list');

        $truck = Truck::create($request->all());
        $truck->contract_code = $request->input('contract_list');
        // from haulers ID
        $truck->subvendor_description = $request->input('hauler_list');
        $truck->card()->associate($card_rfid);
        $truck->capacity()->associate($capacity_id);
        $truck->base()->associate($base_id);
        // $truck->plant()->associate($plant_id);
        $truck->save();


        $truck->contracts()->attach($request->input('contract_list'));
        $truck->plants()->attach($request->input('plant_list'));
        $truck->haulers()->attach($request->input('hauler_list'));

        flashy()->success('Truck has successfully created!');
        return redirect('trucks');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Truck $truck)
    {

        $versions = Version::where('truck_id',$truck->id)->orderBy('created_at','DESC')->get();
        $subcon = Hauler::all();
        $truck_subvendors = collect($this->vendorSubvendor())->where('vendor_number', $truck->vendor_description)->first();
        $truck_vendors = collect($this->vendorSubvendor())->where('vendor_number', $truck->subvendor_description)->first();

        return view('trucks.show', compact('truck','versions','truck_vendors','truck_subvendors','subcon'));
    }

    public function versionVendorName($x)
    {
        $truck_vendors = collect($this->vendorSubvendor())->where('vendor_number', $x)->first();
        return $truck_vendors;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Truck $truck)
    {
        $haulers = ['' => ''] + Hauler::pluck('name','vendor_number')->all();
        $haulers_subcon = ['' => ''] + Hauler::where('vendor_number', '!=', '0000002000')->pluck('name','vendor_number')->all();

         $cards =  Card::orderBy('CardNo','DESC')->pluck('CardNo','CardID')->all();
         $capacities = Capacity::pluck('description','id');
         $contracts = Contract::all()->pluck('contract','id');
         $bases = Base::pluck('origin','id');
         $plants = Plant::pluck('plant_name','id');

         $subvendors = ['NO SUBVENDOR' => 'NO SUBVENDOR'] + collect($this->vendorSubvendor())->where('vendor_number', '!=', '0000002000')->pluck('vendor_name','vendor_number')->all();
         $vendors = collect($this->vendorSubvendor())->pluck('vendor_name','vendor_number');

        return view('trucks.edit', compact('truck','haulers','cards','capacities','contracts','subvendors','vendors','bases','plants','haulers_subcon'));
    }

    // Transfer truck to 3PL
    public function transferHauler(Truck $truck) 
    {
        // $subvendors = collect($this->vendorSubvendor())->where('vendor_number', '!=', '0000002000')->pluck('vendor_name','vendor_number');
        // $vendors = collect($this->vendorSubvendor())->pluck('vendor_name','vendor_number');
        $subcon = Hauler::all();
        $haulers = ['' => ''] + Hauler::pluck('name','vendor_number')->all();
        $haulers_subcon = ['' => ''] + Hauler::where('vendor_number', '!=', '0000002000')->pluck('name','vendor_number')->all();

        return view('trucks.transfer', compact('haulers','haulers_subcon','truck','subcon'));
    }

    // store tranfer truck to 3PL
    public function updateTransferHauler(Request $request, Truck $truck)
    {
        $this->validate($request, [
            'validity_start_date' => 'required|before:validity_end_date',
            'validity_end_date' => 'required',
            'vendor_description' => 'required',
        ]);

        $version = new Version;
        $version->truck_id = $truck->id;
        $version->user_id = Auth::user()->id;
        $version->plate_number = $truck->plate_number;
        $version->reg_number = $truck->reg_number;
        $version->vendor_description = $truck->vendor_description;
        $version->subvendor_description = $truck->subvendor_description;
        $version->contract_code = $truck->contract_code;
        $version->base_id = $truck->base_id;
        $version->start_validity_date = $request->input('validity_end_date');
        $version->end_validity_date = Carbon::now();
        $version->save(); 
    
        $truck->update($request->all());            

        $activity = activity()
        ->performedOn('App\Truck')  
        ->log('Transferred to 3PL');

        flashy()->success('Truck has successfully transferred!');
        return redirect('trucks');
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
            // 'plate_number' => 'required|max:8|min:8',
            'card_list' => 'required',
            'capacity_list' => 'required',
            'contract_list' => 'required',
            'hauler_list' => 'required',
            'validity_start_date' => 'required',
            'vendor_description' => 'required',
            'base_list' => 'required',
            'plant_list' => 'required',
            'validity_start_date' => 'required|before:validity_end_date',
            'validity_end_date' => 'required',
        ],[
            'card_list.required' => 'RFID Number is required',
            'capacity_list.required' => 'Capacity Field is required',
            'contract_list.required' => 'Contract Code is required',
        ]);

        $card_rfid = $request->input('card_list');
        $capacity_id = $request->input('capacity_list');
        $base_id = $request->input('base_list');
        $plant_id = $request->input('plant_list');

        $truck->update($request->all());
        $truck->card()->associate($card_rfid);
        $truck->capacity()->associate($capacity_id);
        $truck->base()->associate($base_id);
        $truck->plant()->associate($plant_id);
        $truck->save();

        $truck->haulers()->sync( (array) $request->input('hauler_list'));
        
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
