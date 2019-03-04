<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;
use App\Cardholder;
use App\Pickup;
use App\Log;
use Flashy;

class PickupsApiController extends Controller
{
    public function unserved()
    {
        $unserved = Pickup::whereNull('cardholder_id')
                        ->orderBy('created_at','DESC')
                        // ->get()
                        ->paginate(10);

        return $unserved;
    }

    public function assigned()
    {
        $assigned = Pickup::whereDate('activation_date', Carbon::today())
                        ->whereNull('deactivated_date')
                        ->whereNotNull('cardholder_id')
                        ->orderBy('id','DESC')
                        // ->take(8)
                        // ->get();
                        ->paginate(10);

        return $assigned;
    }

    public function served()
    {
        $served = Pickup::whereDate('deactivated_date', Carbon::today())
                    ->whereNotNull('deactivated_date')
                    ->whereNotNull('cardholder_id')
                    ->orderBy('created_at','DESC')
                    // ->take(1)
                    // ->get();
                    ->paginate(10);

        return $served;
    }

    public function searchPickups(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'required|before:end_date',
            'end_date' => 'required'
        ]);

        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $this->assigned();
        $this->served();
        $this->unserved();

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
