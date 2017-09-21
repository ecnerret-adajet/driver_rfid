<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\User;
use Carbon\Carbon;

class ActivitiesController extends Controller
{
    public function index()
    {
        $users = User::all();
        $activities = Activity::where('created_at', '>=', Carbon::now()->subDay())
                            ->orderBy('id','DESC')->get();    

        return view('activities.index', compact('activities','users'));
    }

    public function generateActivities(Request $request)
    {
        $this->validate($request,[
            'start_date' => 'required|before:end_date',
            'end_date' => 'required'
        ]);

        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $users = User::all();
        $activities = Activity::whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)])
                            ->orderBy('id','DESC')->get(); 
    
        return view('activities.index', compact('activities','users'));
    }

    public function findUser($find)
    {
        $user = User::select('name')->where('id',$find)->first();
        return $user;
    }
}
