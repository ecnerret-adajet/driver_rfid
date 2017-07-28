<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('drivers');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/prints','PrintController@index');
Route::get('/forPrint','PrintController@getForPrint');
Route::post('/prints/{id}','PrintController@printed');

Route::resource('/drivers','DriversController');

Route::get('/driversJson', function () {
    $drivers = App\Driver::with(['haulers','trucks'])->get();
    return $drivers;
});


});

