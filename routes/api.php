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

Route::get('pickups-unserved/{date}','PickupsApiController@unserved');
Route::get('pickups-assigned/{date}','PickupsApiController@assigned');
Route::get('pickups-served/{date}','PickupsApiController@served');
Route::patch('pickups-deactivate/{pickup}','PickupsApiController@pickupDeactivate');
Route::get('pickups-available','PickupsApiController@cardholderAvailability');
Route::patch('pickups-assign-card/{pickup}','PickupsApiController@assignCardholder');

});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group(['middleware' => 'auth:api'], function() {

//     Route::resource('replacements','Api\ReplacementApiController');

// });


// API setup for Hauler Online



// API setup for Pickup Online
