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
use DB;

class TrucksController extends Controller
{

    /**
     * 
     *  Custom Function
     * 
     */

     // return vendor from SAP server
    public function versionVendorName($x)
    {
        $truck_vendors = collect($this->vendorSubvendor())->where('vendor_number', $x)->first();
        return $truck_vendors;
    }

    // return array from SAP server
    public function vendorSubvendor()
    {
        $url = "http://10.96.4.39/trucking/rfc_get_vendor.php?server=lfug";
        $result = file_get_contents($url);
        $data = json_decode($result,true);
        return $data;
    }

    // return haulers vendor number for edit method
    public function vendorHauler($x) 
    {
        $hauler = Hauler::where('id',$x)->first();
        return $hauler->vendor_number;
    }

    // returns hauler vendor number for edit method
    public function truckHauler($x)
    {
        $truck = Truck::where('id',$x)->first();
        foreach($truck->haulers as $hauler)
        {
            $hauler_name = $hauler->id;
        }
        return $hauler_name;
    }

    //returns hauler vendor number for edit method
    public function subvendorHauler($x)
    {
        $hauler = Hauler::where('id',$x)->first();
        return $hauler->vendor_number;
    }

    //return vendor name from hauler's table
    public function haulerName($x)
    {
        $hauler = Hauler::where('id', $x)->first();
        return $hauler->name;
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
     * 
     * 
     *  Search Cardholder Name for cards 
     * 
     */
    public function displayCardholder($x)
    {
        $cardholder = Cardholder::where('CardholderID',$x)->first();
        return $cardholder->Name;
    }



    /**
     * Show all cardholder should not be displayed on card list 
     */
    public function removedCardholder()
    {
        $pickup_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%pickup%')
        ->pluck('CardholderID'); 

        $guard_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%GUARD%')
        ->pluck('CardholderID'); 

        $executive_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%EXECUTIVE%')
        ->pluck('CardholderID'); 

        $driver_card = Driver::select('cardholder_id')
        ->where('availability',1)
        ->pluck('cardholder_id');

        $get_truck = Truck::select('card_id')
        ->whereNotNull('card_id')
        ->pluck('card_id');

        $truck_card = Card::whereIn('CardID',$get_truck)->pluck('CardholderID');

        // Remove all cardholder without driver assigned
        $not_driver = array_collapse([$pickup_cards, $guard_cards, $executive_cards, $driver_card, $truck_card]);

        return $not_driver;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $haulers = ['' => ''] + Hauler::pluck('name','id')->all();

        $haulers_subcon = ['' => ''] + Hauler::where('vendor_number', '!=', '0000002000')->pluck('name','id')->all();
        
        $driver_card = Driver::select('cardholder_id')->where('availability',1)->get();
        $truck_card = Truck::select('card_id')->whereNotNull('card_id')->get();

        $cards = Card::orderBy('CardholderID','DESC')
                    ->whereNotIn('CardholderID', $this->removedCardholder())
                    ->where('AccessgroupID', 2) // card type
                    ->where('CardholderID','>=', 15)
                    ->where('CardholderID','!=', 0)
                    ->get()
                    ->pluck('full_deploy','CardID');

        $capacities = Capacity::pluck('description','id');

        $contracts = ['' => ''] + Contract::all()->pluck('contract','id')->all();

        $bases = Base::pluck('origin','id');

        $plants = Plant::pluck('plant_name','id');

        return view('trucks.create', compact('card_test','haulers','cards','capacities','contracts','subvendors','vendors','bases','plants','haulers_subcon'));
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
            'documents' => 'required',
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

        $truck = Auth::user()->trucks()->create($request->all());
        if($request->hasFile('documents')){
            $truck->documents = $request->file('documents')->store('trucks_docs');
        }   
        if(empty($request->input('plate_number'))){
            $truck->plate_number = $request->input('reg_number');
        }
        $truck->contract_code = $request->input('contract_list');
        $truck->subvendor_description = $request->input('hauler_list');
        $truck->card()->associate($card_rfid);
        $truck->capacity()->associate($capacity_id);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Truck $truck)
    {
        $haulers = ['' => ''] + Hauler::orderBy('vendor_number','ASC')->pluck('name','id')->all();
        $haulers_subcon = ['' => ''] + Hauler::where('vendor_number', '!=', '0000002000')->pluck('name','id')->all();

        if(!count($truck->drivers) == null) {
            foreach($truck->drivers as $driver) {
                $driver_card = Driver::where('id',$driver->id)
                ->where('availability',1)->first();
            }
            
            $cards = Card::orderBy('CardholderID','DESC')
            ->whereNotIn('CardholderID', $this->removedCardholder())
            ->where('AccessgroupID', 2) // card type
            ->where('CardholderID','>=', 15)
            ->where('CardholderID','!=', 0)
            ->get()
            ->pluck('full_deploy','CardID');


        } else {
            $has_driver = Driver::select('cardholder_id')->where('availability',1)->get();
            // show all RFID when there is no driver
            $cards = Card::orderBy('CardholderID','DESC')
            ->whereNotIn('CardholderID', $this->removedCardholder())
            ->where('AccessgroupID', 2) // card type
            ->where('CardholderID','>=', 15)
            ->where('CardholderID','!=', 0)
            ->get()
            ->pluck('full_deploy','CardID');

        }
         
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
        $haulers = ['' => ''] + Hauler::pluck('name','id')->all();
        $haulers_subcon = ['' => ''] + Hauler::where('vendor_number', '!=', '0000002000')->pluck('name','id')->all();
        
        return view('trucks.transfer', compact('haulers','haulers_subcon','truck'));
    }

    // store tranfer truck to 3PL
    public function updateTransferHauler(Request $request, Truck $truck)
    {
        $this->validate($request, [
            'hauler_list' => 'required',
            'validity_start_date' => 'required|before:validity_end_date',
            'validity_end_date' => 'required',
        ]);

        $version = new Version;
        $version->truck_id = $truck->id;
        $version->user_id = Auth::user()->id;
        $version->plate_number = $truck->plate_number;
        $version->reg_number = $truck->reg_number;
        $version->vendor_description = $truck->vendor_description;
        $version->subvendor_description = $truck->subvendor_description;
        $version->contract_code = $truck->contract_code;
        // $version->base_id = $truck->base_id;
        $version->start_validity_date = $request->input('validity_end_date');
        $version->end_validity_date = Carbon::now();
        $version->save(); 

        $hauler_name = Hauler::select('name')->where('id',$request->input('hauler_list'))->first();
    
        $truck->update($request->all());  
        $truck->vendor_description = $request->input('vendor_description');
        $truck->subvendor_description = $request->input('hauler_list');
        $truck->save();
        
        $truck->haulers()->sync((array) $request->input('hauler_list'));      
            
        $activity = activity()
        ->log('Transferred to 3PL');

        flashy()->success('Truck has successfully transferred!');
        return redirect('trucks');
    }

    /*
    *
    * Remove Driver
    *
    */
    public function removeDriver(Request $request, $id)
    {
        $truck = Truck::findOrFail($id);
        $truck->drivers()->sync((array) null);

        flashy()->success('Driver successfully removed!');
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

        // Commented Temporarily
        //  $this->validate($request, [
        //     // 'plate_number' => 'required|max:8|min:8',
        //     'card_list' => 'required',
        //     'capacity_list' => 'required',
        //     'contract_list' => 'required',
        //     'hauler_list' => 'required',
        //     'validity_start_date' => 'required',
        //     'vendor_description' => 'required',
        //     // 'base_list' => 'required',
        //     'plant_list' => 'required',
        //     'validity_start_date' => 'required|before:validity_end_date',
        //     'validity_end_date' => 'required',
        // ],[
        //     'card_list.required' => 'RFID Number is required',
        //     'capacity_list.required' => 'Capacity Field is required',
        //     'contract_list.required' => 'Contract Code is required',
        // ]);

        $card_rfid = $request->input('card_list');
        $capacity_id = $request->input('capacity_list');

        $truck->update($request->all());
        if($request->hasFile('documents')){
            $truck->documents = $request->file('documents')->store('trucks_docs');
        }
        $truck->contract_code = $request->input('contract_list');
        $truck->subvendor_description = $request->input('hauler_list');
        $truck->vendor_description = $request->input('vendor_description');
        $truck->card()->associate($card_rfid);
        $truck->capacity()->associate($capacity_id);
        $truck->save();

        $truck->haulers()->sync((array) $request->input('hauler_list'));
        // $truck->contracts()->sync((array) $request->input('contract_list'));
        // $truck->plants()->sync((array) $request->input('plant_list'));


        flashy()->success('Truck has successfully updated!');
        return redirect('trucks');
    }

    /*
    *
    * Deactivate a truck
    *
    */
    public function deactivateTruck(Request $request, $id)
    {
        $truck = Truck::where('id',$id)->first();
        $truck->availability = 0;
        $truck->save();

        flashy()->success('Truck has successfully deactivated!');
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
