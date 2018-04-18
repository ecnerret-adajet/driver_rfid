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
        $serving = Serve::with('driver','driver.truck','driver.hauler','user','driver.image')
                            ->orderBy('id','DESC')
                            ->where('on_serving',1)
                            ->take(1)
                            ->get();
        
        return $serving;
    }

    public function servedToday()
    {

        $current_serving = Serve::with('driver','driver.truck','driver.hauler','driver.image','user')
                        ->orderBy('id','DESC')
                        ->where('on_serving',1)
                        ->take(1)
                        ->pluck('id');

        $served = Serve::with('driver','driver.trucks','driver.haulers','driver.image','user')
                        ->orderBy('id','DESC')
                        ->whereNotIn('id',$current_serving)
                        ->where('on_serving',1)
                        ->take(4)
                        ->get();
        
          $arr = array();

           foreach($served as $x) {
                $data = array( 
                    'served_id' => $x->id,
                    'driver_id' => $x->driver->id,
                    'avatar' => !empty($x->driver->image) ? $x->driver->image->avatar : $x->driver->avatar,
                    'on_servering' => $x->on_serving,
                    'served_start' => $x->served_start_date,
                    'served_end_date' => $x->served_end_date,
                    'driver_name' => empty($x->driver->name) ? null : $x->driver->name,
                    'plate_number' => empty($x->driver->trucks) ? null : $x->driver->trucks->first()->plate_number,
                    'hauler_name' => empty($x->driver->haulers) ? null : $x->driver->haulers->first()->name,
                    'user_name' => empty($x->user->name) ? null : $x->user->name,
                );

                array_push($arr, $data);
           }
        
           return $arr;

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
