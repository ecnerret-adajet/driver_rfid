<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Card;
use App\Rfid;
use App\Cardholder;

class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cards.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
    public function edit($CardID)
    {
        $card = Card::where('CardID',$CardID)->first();
        $rfids = Rfid::pluck('name','id');
        // for testing
        $cardholders = Cardholder::pluck('Name','CardholderID');
        return view('cards.edit', compact('card','rfids','cardholders'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {
        $this->validate($request, [
            'cardholder_id'
        ]);

        $cardholder_id = $request->input('cardholder');
        $card->cardholder()->associate($cardholder_id);
        $card->save();
        // $card->rfids()->sync($request->input('rfid_list'));

        return redirect('cards');

        
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
