<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Driver;
use App\Serve;
use DB;
use Flashy;

class ServingController extends Controller
{

    public function currentlyServing()
    {
        $serving = Serve::with('driver','driver.truck','driver.hauler','user')
                            ->orderBy('id','DESC')
                            ->where('on_serving',1)
                            ->take(1)
                            ->get();
        
        return $serving;
    }

    public function storeCurrentlyServing(Request $request, $id)
    {
        $serving = new Serve;
        $serving->user_id = Auth::user()->id;
        $serving->driver_id = $id;
        $serving->on_serving = 1;
        $serving->served_start_date = Carbon::now();
        $serving->save();

        flashy()->success('Driver successfully on serve!');
        return redirect('monitor/feed');

    }

}
