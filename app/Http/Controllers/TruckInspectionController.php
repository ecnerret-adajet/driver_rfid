<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Inspection;
use App\Truck;
use Carbon\Carbon;
use App\AccessGroup;
use App\Card;
use App\Driverqueue;

class TruckInspectionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * View Truck Deactivate / Activation History
     */
    public function inspectionHistory(Truck $truck)
    {
        return view('inspects.history', compact('truck'));
    }

    public function deactivateTruckCreate(Truck $truck)
    {

        if(Gate::denies('deactivate', $truck)) {
            flashy()->error('The current truck was still activated');
            return back();            
        }

        $access_groups = ['1' => 'All location'] + AccessGroup::where('AccessGroupName', 'LIKE', '%TM%')->pluck('AccessGroupName','AccessGroupID')->all();

        $driverlocations = ['0' => 'All location'] + Driverqueue::pluck('title','id')->all();


        return view('inspects.deactivate', compact('truck','access_groups','driverlocations'));
    }

    public function deactivateTruckStore(Request $request, Truck $truck)
    {

        /**
         * Connected vue component:
         * 
         * 1. Trucks.vue
         * 2. GateArea.vue
         */

        $this->validate($request,[
            'remarks' => 'required|min:3',
            'plant_deactivated' => 'required'
        ]);

        $inspection = Auth::user()->inspections()->create([
            'remarks' => $request->input('remarks'),
            'truck_id' => $truck->id
        ]);

        if(!empty($truck->driver)) {

        $card = Card::where('CardholderID',$truck->driver->cardholder_id)
                ->where('AccessGroupID',1);        

        switch ($request->input('plant_deactivated')) {
            case '0':                
                $card->update(['AccessGroupId'=>'1']);
                $inspection->accessLocation()->associate(11); // because 1 is not available
                $inspection->save();

                $truck->availability = 0;
                break;

            case '1':
                $card->update(['AccessGroupId'=>'5']);
                $inspection->accessLocation()->associate($request->input('plant_deactivated'));
                $inspection->save();

                $truck->accessLocation()->associate($request->input('plant_deactivated'));
                break;
            
            case '2':
                $card->update(['AccessGroupId'=>'6']);
                $inspection->accessLocation()->associate($request->input('plant_deactivated'));
                $inspection->save();

                $truck->accessLocation()->associate($request->input('plant_deactivated'));
                break;

            case '3':
                $card->update(['AccessGroupId'=>'7']);
                $inspection->accessLocation()->associate($request->input('plant_deactivated'));
                $inspection->save();

                $truck->accessLocation()->associate($request->input('plant_deactivated'));
                break;
                
        } // end switch

        } // end if 
        
        $truck->save();


        flashy()->success('Truck has successfully created!');
        return redirect('trucks');
    }

    public function activateTruckCreate(Truck $truck)
    {

        if(Gate::denies('activate', $truck)) {
            flashy()->error('The current truck was still deactivated');
            return back();            
        }

        $driverlocations = Driverqueue::pluck('title','id');
        return view('inspects.activate', compact('truck','driverlocations'));
    }

    public function activateTruckStore(Request $request, Truck $truck)
    {
        $this->validate($request,[
            'remarks' => 'required|min:3'
        ]);

        if(!empty($truck->driver)) {

        $card = Card::where('CardholderID',$truck->driver->cardholder_id)
                ->where('AccessGroupID',1)
                ->update(['AccessGroupId'=>'1']);  
        }
        
        // Set Truck to activate
        $truck->availability = 1;
        $truck->accessLocation()->associate(0); // return truck to active state
        $truck->save();

        $inspection = Auth::user()->inspections()->create([
            'remarks' => $request->input('remarks'),
            'status' => 1,
            'truck_id' => $truck->id
        ]);

        flashy()->success('Truck has successfully created!');
        return redirect('trucks');
    }

}
