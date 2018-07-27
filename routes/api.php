<?php

// use Illuminate\Http\Request;
// use App\Driver;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/last_dr_date/{plate_number}','EntryReportController@getLastDrSubmitted');

// API setup for Hauler Online



// API setup for Pickup Online
