<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Queue;
use App\Log;
use Flashy;
use Carbon\Carbon;

class QueuesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $log_queues = Log::with(['drivers','drivers.trucks','drivers.haulers'])
        ->where('ControllerID',1)
        ->where('DoorID',0)
        ->where('CardholderID', '>=', 15)
        ->where('LocalTime', '>=', Carbon::now())
        ->orderBy('LogID','DESC')->get();

        $queues = Queue::all();
        
        return view('queues.index', compact('log_queues','queues'));
    }

    /**
    *
    *
    * Generater Record form queue model database
    *
    */
    public function generateQueues(Request $request)
    {
        $this->validate($request,[
            'start_date' => 'required|before:end_date',
            'end_date' => 'required'
        ]);

        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $queues = Queue::all();

        $log_queues = Log::with(['drivers','drivers.trucks','drivers.haulers'])
        ->where('ControllerID',1)
        ->where('DoorID',0)
        ->where('CardholderID', '>=', 15)
        ->whereBetween('LocalTime', [Carbon::parse($start_date), Carbon::parse($end_date)])
        ->orderBy('LogID','DESC')->get();

        return view('queues.index', compact('log_queues','queues'));
    }

    public function queueJson()
    {
        $log_queues = Log::with(['drivers','drivers.trucks','drivers.haulers'])
        ->where('ControllerID',1)
        ->where('DoorID',0)
        ->where('CardholderID', '>=', 15)
        ->where('LocalTime', '>=', Carbon::now())
        ->orderBy('LogID','DESC')->get();

        return $log_queues;
    }

    public function markedJson()
    {
        $marked = Queue::whereDate('created_at',Carbon::now())->count(); 
        return $marked;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($log)
    {
        return view('queues.create',compact('log'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $log)
    {
        

        $queue = new Queue;
        $queue->user_id = Auth::user()->id;
        $queue->LogID = $log;
        $queue->availability = 0;
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
