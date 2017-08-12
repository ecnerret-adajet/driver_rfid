<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Card;
use App\Rfid;
use App\Binder;

class BindersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($CardID)
    {
        $card = Card::where('CardID',$CardID)->first();
        $rfids = Rfid::pluck('name','id');
        $cards = Card::pluck('CardNo','CardID');
        return view('binders.create',compact('rfids','cards','card'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $CardID)
    {
        $this->validate($request, [
            'rfid_list' => 'required',
        ]);

        $rfid = $request->input('rfid_list');
        $binder = new Binder;
        $binder->card()->associate($CardID);
        $binder->rfid()->associate($rfid);
        $binder->save();


        return redirect('cards');
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
