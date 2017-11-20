<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use App\User;
use Auth;
use App\Role;
use DB;
use Image;
use Hash;
use App\Permission;
use Flashy;
use App\Hauler;
use App\Driver;
use App\Truck;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('display_name','id');
        $haulers = Hauler::pluck('name','id');
        return view('users.create',compact('roles','haulers'));  
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'roles_list' => 'required',
        ]);

        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        // $user->position = $request->input('position');
        $user->password = Hash::make($request->input('password'));
        
        if($request->hasFile('avatar')){
        $user->avatar = $request->file('avatar')->store('users');
        }
        $user->hauler()->associate($request->input('hauler_list'));
        $user->save();

         $user->roles()->sync( (array) $request->input('roles_list') );

         $activity = activity()
         ->log('Created');
        
         flashy()->success('User has successfully created!');

        return redirect('users');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('display_name','id');
        $roles = Role::pluck('display_name','id');
        $userRole = $user->roles->pluck('id','id')->toArray();
        $haulers = Hauler::pluck('name','id');

        return view('users.edit',compact('user','roles','userRole','haulers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));    
        }

        $user->update($input);
        $user->roles()->sync( (array) $request->input('roles_list'));
        $user->hauler()->associate($request->input('hauler_list'));
        $user->save();

        $activity = activity()
        ->log('Updated');

        flashy()->success('Driver has successfully updated!');
        return redirect('users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        $activity = activity()
        ->log('Deleted');

        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }

    public function haulerOnline()
    {
        return view('users.haulers-online');
    }

    public function userDriverHauler($id)
    {
        $user = User::findOrFail($id);

        if(!empty($user->hauler_id)) {
            $drivers = Driver::whereHas('haulers', function($q) use ($user) {
                $q->where('id',$user->hauler_id);
            })->with(['haulers','trucks','cardholder','card','cardholder.logs'])
            ->orderBy('id','DESC')
            ->get();
    
        }
        
        return $drivers;
    }

    public function userTruckHauler($id)
    {
        $user = User::findOrFail($id);

        if(!empty($user->hauler_id)){
            $trucks = Truck::whereHas('haulers', function($q) use ($user) {
                $q->where('id',$user->hauler_id);
            })->with(['drivers','haulers','drivers.cardholder','card','hauler'])
            ->orderBy('id','DESC')
            ->get();
        }

        return $trucks;
    }
}
