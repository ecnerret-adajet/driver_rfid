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

// Route::get('/last_dr_date/{plate_number}','EntryReportController@getLastDrSubmitted');

Route::group(['middleware' => 'auth:api'], function() {

Route::get('pickups-unserved','PickupsApiController@unserved');
Route::get('pickups-assigned','PickupsApiController@assigned');
Route::get('pickups-served','PickupsApiController@served');

});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// API setup for Hauler Online



// API setup for Pickup Online
