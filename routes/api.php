<?php

use Illuminate\Http\Request;
use App\Driver;

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

Route::get('/queues','LineupApiController@getDriverQue');
Route::get('/checkSubmissionDate/{plate_number}','LineupApiController@checkSubmissionDate');
