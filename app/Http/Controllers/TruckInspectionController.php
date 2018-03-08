<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Inspection;
use App\Truck;
use Carbon\Carbon;

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
        return view('inspects.deactivate', compact('truck'));
    }

    public function deactivateTruckStore(Request $request, Truck $truck)
    {
        $this->validate($request,[
            'remarks' => 'required|min:3'
        ]);

        $inspection = Auth::user()->inspections()->create([
            'remarks' => $request->input('remarks'),
            'truck_id' => $truck->id
        ]);

        // Set Truck to deactivate
        $truck->availability = 0;
        $truck->save();

        flashy()->success('Truck has successfully created!');
        return redirect('trucks');
    }

    public function activateTruckCreate(Truck $truck)
    {
        return view('inspects.activate', compact('truck'));
    }

    public function activateTruckStore(Request $request, Truck $truck)
    {
        $this->validate($request,[
            'remarks' => 'required|min:3'
        ]);

        $inspection = Auth::user()->inspections()->create([
            'remarks' => $request->input('remarks'),
            'status' => 1,
            'truck_id' => $truck->id
        ]);
        
        // Set Truck to activate
        $truck->availability = 1;
        $truck->save();

        flashy()->success('Truck has successfully created!');
        return redirect('trucks');
    }

}
