<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Queue;
use App\Log;
use Flashy;

class QueuesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('queues.index');
    }

    public function queueJson()
    {
        $log_queues = Log::with(['drivers','drivers.trucks','drivers.haulers'])
        ->where('ControllerID',1)
        ->where('DoorID',0)
        ->where('CardholderID', '>=', 15)
        ->orderBy('LogID','DESC')->get();

        return $log_queues;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($log)
    {
        return view('queues.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $log)
    {
        $this->validate($request, [
            'availability' => 'required'
        ]);

        $log_id = Log::select('LogID')->where('LogID',$log)->first();

        $queue = Auth::user()->queues()->create($request->all());
        $queue->LogID = $log_id;
        $queue->save();

        flashy()->success('Queue has successfully created!');
        return redirect('queues');

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
