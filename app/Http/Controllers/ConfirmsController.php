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
use App\Card;

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

    /*
    *
    * Show all pendin for approval drivers
    *
    */
    public function pending()
    {
        $confirms = Confirm::where('user_id',Auth::user()->id)
                            ->orderBy('created_at','DESC')
                            ->get();

        return view('confirms.pending',compact('confirms'));
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

        $driver = Driver::findOrFail($id);

        $confirm = new Confirm;
        $confirm->fill($request->all());
        $confirm->driver()->associate($id);
        $confirm->user()->associate(Auth::user()->id);

        if($driver->availability == 0 && $driver->print_status == 1 && $driver->notif_status == 0) {
            $confirm->classification = 'New Driver';
        }

        if($driver->created_at != $driver->updated_at) {
            $confirm->classification = 'Update Driver';
        }

        $confirm->save();

        if($confirm->status == 'Approve') {

            $driver->notif_status = 1;
            $driver->availability = 1;

            if(!empty($driver->card_id)) {
                $card = Card::where('CardID',$driver->card_id)->first();
                $card->CardStatus = 0; 
            }

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
