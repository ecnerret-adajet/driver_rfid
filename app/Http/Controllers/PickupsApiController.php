<?php

namespace App\Http\Controllers;

use App\Transformers\EntryReportTransformer;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Cardholder;
use Carbon\Carbon;
use App\Pickup;
use App\Log;
use Flashy;

class PickupsApiController extends Controller
{
    public function unserved()
    {
        $unserved = Pickup::whereNull('cardholder_id')
                        ->orderBy('created_at','DESC')
                        // ->get();
                        ->paginate(10);

        return response()->json($unserved, 200);
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
