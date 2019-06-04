<?php

namespace App\Http\Controllers\Api;

use App\Transformers\ReplacementTransformer;
use League\Fractal\Resource\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Replacement;
use Carbon\Carbon;
use App\Card;

class ReplacementApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $replacements = Replacement::all();

        $manager = new Manager();
        $resource = new Collection($replacements, new ReplacementTransformer());

        return $manager->createData($resource)->toArray();
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
     * Show all available RFID
     */
    public function driverRfidList()
    {
        $cards = Card::orderBy('CardholderID','DESC')
                    ->whereNotIn('CardholderID', $this->removedCardholder())
                    ->where('AccessGroupID', 1) // card type
                    ->where('CardholderID','>=', 15)
                    ->where('CardholderID','!=', 0)
                    ->get();

        return $cards;
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
            'driver_id' => 'required',
            'card_id' => 'required',
            'reason_replacement' => 'required',
        ]);

        $replacement = Replacement::create([
            'user_id' => $request->user_id,
            'driver_id' => $request->driver_id,
            'card_id' => $request->card_id,
            'reason_replacement' => $request->reason_replacement,
            'remarks' => $request->remarks,
        ]);

        $manager = new Manager();
        $item = new Item($replacement, new ReplacementTransformer);

        return $manager->createData($item)->toArray()['data'];

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
