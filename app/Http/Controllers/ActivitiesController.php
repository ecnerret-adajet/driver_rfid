<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\User;

class ActivitiesController extends Controller
{
    public function index()
    {
        $users = User::all();
        $activities = Activity::all();    

        return view('activities.index', compact('activities','users'));

    }
}
