<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Handler;
use App\Hauler;
use JavaScript;

class HandlerMappingsController extends Controller
{

    public function lfugServer()
    {
        // $url = "http://10.96.4.39/trucking/rfc_get_vendor.php?server=lfug";
        $url = "http://10.96.4.39/sapservice/api/vendors/lfug";

        $result = file_get_contents($url);
        $data = json_decode($result,true);

        return $data;
    }

    public function pfmcServer()
    {
        // $url = "http://10.96.4.39/trucking/rfc_get_vendor.php?server=pfmc";
        $url = "http://10.96.4.39/sapservice/api/vendors/pfmc";
        $result = file_get_contents($url);
        $data = json_decode($result,true);

        return $data;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('handlers.index');
    }

    public function getHandlerJson()
    {
        $handlers = Handler::orderBy('id','DESC')->get();
        return $handlers;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lfug = collect($this->lfugServer())->pluck('vendor_name','vendor_number');
        $pfmc = collect($this->pfmcServer())->pluck('vendor_name','vendor_number');

        return view('handlers.create', compact('lfug','pfmc'));
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
            'lfug_server' => 'required',
            'pfmc_server' => 'required',
        ]);

        $lfug = (object)collect($this->lfugServer())->where('vendor_number',$request->input('lfug_server'))->first();
        
        $hauler = new Hauler;
        $hauler->name = $lfug->vendor_name;
        $hauler->vendor_number  = $request->input('lfug_server');
        $hauler->save();


       $handler = Handler::insert([
            [
             'hauler_id' => $hauler->id,
             'vendor_number'=> $request->input('lfug_server'),
             'server_id'=> '1'
             ],
             [
             'hauler_id' => $hauler->id,
             'vendor_number'=> $request->input('pfmc_server'),
             'server_id'=> '2'
             ]
            ]);

      

        flashy()->success('Handler Mapping has successfully created!');
        return redirect('handlers');
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
