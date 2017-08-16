<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Truck;
use App\Driver;
use App\Hauler;
use App\Card;

class TrucksController extends Controller
{
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
        // for testing
        // $rfid_card = Card::whereHas('binders', function($q) {
        //     $q->where('card_id','2');
        // });

        $haulers = ['' => ''] + Hauler::pluck('name','id')->all();
        $cards = ['' => ''] + Card::pluck('CardNo','CardID')->all();
        return view('trucks.create', compact('haulers','cards'));
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
            'plate_number' => 'required|unique:trucks',
            'hauler_list' => 'required',
            'card_list' => 'required'
        ]);

        $card_rfid = $request->input('card_list');
        $truck = Truck::create($request->all());
        $truck->card()->associate($card_rfid);
        $truck->save();


        $truck->haulers()->attach($request->input('hauler_list'));


        return redirect('trucks');
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
    public function edit(Truck $truck)
    {
         $haulers = Hauler::pluck('name','id');
         $cards = ['' => ''] + Card::pluck('CardNo','CardID')->all();
        return view('trucks.edit', compact('truck','haulers','cards'));
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
            'plate_number' => 'required',
            'hauler_list' => 'required',
            'card_list' => 'required'
        ]);

        $card_rfid = $request->input('card_list');
        $truck->update($request->all());
        $truck->card()->associate($card_rfid);
        $truck->save();

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
}
