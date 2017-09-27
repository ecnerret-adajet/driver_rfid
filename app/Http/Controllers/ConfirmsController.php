<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ConfirmAdmin;
use App\Setting;
use App\Driver;
use App\Confirm;
use App\User;

class ConfirmsController extends Controller
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
    public function create($id, $plate)
    {   
        $driver = Driver::findOrFail($id);
        return view('confirms.create', compact('id','driver','plate'));
    }

    /**
     * Store a newly stored resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id, $plate)
    {
        $this->validate($request, [
            'status',
            'remarks'
        ]);

        $confirm = new Confirm;
        $confirm->fill($request->all());
        $confirm->driver()->associate($id);
        $confirm->user()->associate(Auth::user()->id);
        $confirm->save();

        if($confirm->status == 'Approve') {

            $driver = Driver::findOrFail($id);
            $driver->notif_status = 1;
            $driver->availability = 1;
            $driver->save();

             //send email to supervisor for approval
            $setting = Setting::with('user')->where('id',2)->first();
            Notification::send(User::where('id', $setting->user->id)->get(), new ConfirmAdmin($confirm,$driver));

        }

        return redirect('prints');
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
