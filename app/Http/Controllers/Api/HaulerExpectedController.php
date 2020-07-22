<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\ExpectedArrival;
use App\User;
use App\Truck;

class HaulerExpectedController extends Controller
{

    /**
     * Truck expected arrival view blade
     *
     * @return void
     */
    public function expectedArrivals()
    {
        return view('expected.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expectedArrival = ExpectedArrival::with('truck','truck.driver','hauler')
                    ->orderBy('id','DESC')
                    ->get();

        return $expectedArrival;

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
        $this->validate($request, [
            'truck_id' => 'required',
            'expected_arrival_date' => 'required',
            'expected_arrival_time' => 'required',
        ]);

        $truck = Truck::where('id',$request->input('truck_id'))
                    ->with('hauler')
                    ->orderBy('id','DESC')
                    ->first();

        $expectedArrival = new ExpectedArrival;
        $expectedArrival->user_id = Auth::user()->id;
        $expectedArrival->expected_arrival = date('Y-m-d H:i:s', strtotime($request->input('expected_arrival_date') . ' ' . $request->input('expected_arrival_time')));
        $expectedArrival->hauler_id = Auth::user()->hauler->id;
        $expectedArrival->truck()->associate($request->input('truck_id'));
        $expectedArrival->plate_number = $truck->plate_number;
        $expectedArrival->hauler_name = $truck->hauler->name;
        $expectedArrival->remarks = $request->input('remarks');
        $expectedArrival->save();

        flashy()->success('Pickup has successfully created!');

        return $expectedArrival;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        $expectedArrival = ExpectedArrival::where('hauler_id', $user->hauler->id)
            ->with('truck', 'truck.driver', 'hauler')
            ->orderBy('id', 'DESC')
            ->get();

        return $expectedArrival;
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
