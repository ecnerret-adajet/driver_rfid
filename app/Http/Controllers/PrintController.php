<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Driver;
use App\Cardholder;
use App\Truck;
use App\Hauler;
use App\Confirm;

class PrintController extends Controller
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

    public function index()
    {
        /**
         *  Check for new or reassign drivers for approval
         * 
         */
        $print = Driver::orderBy('id','DESC')
                ->where('print_status',1)
                ->orWhere('notif_status',1)
                ->with(['haulers','trucks','clasification','user','confirms'])->get();
                
        return view('prints.index', compact('print'));
    }

    public function getForPrint()
    {
        $print = Driver::where('update_count',1)
                ->with(['haulers','trucks','clasification','user'])->get();
        return $print;
    }

    /**
    * Change Print Status 
    *
    */
    public function printed(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);
        $driver->print_status = 0;
        $driver->notif_status = 0;
        $driver->save();

        return redirect('prints');
    }

}
