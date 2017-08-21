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
    return view('home');
})->middleware('auth');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/logs','LogsController@index');
Route::get('/entriesIn','LogsController@entriesIn');
Route::get('/entriesOut','LogsController@entriesOut');



Route::get('/prints','PrintController@index');
Route::get('/forPrint','PrintController@getForPrint');
Route::post('/prints/{id}','PrintController@printed');

Route::resource('/drivers','DriversController');
Route::get('/exportDrivers','DriversController@exportDrivers');

Route::resource('/trucks','TrucksController');

Route::resource('/haulers','HaulersController');

Route::get('/confirm/create/{id}','ConfirmsController@create');
Route::post('/confirm/{id}','ConfirmsController@store');

Route::get('/driversJson', function () {
    $drivers = App\Driver::with(['haulers','trucks','cardholder','card'])->get();
    return $drivers;
});

// return Json results

Route::get('/trucksJson', function() {
    $trucks = App\Truck::with(['drivers','haulers','drivers.cardholder','card'])->get();
    return $trucks;
});

Route::get('/haulersJson', function() {
    $haulers = App\Hauler::with(['drivers','trucks'])->get();
    return $haulers;
});

Route::get('/settingsJson', function() {
    $settings = App\Setting::with('user')->get();
    return $settings;
});

Route::get('/cardsJson', function() {
    $cards = App\Card::with(['binders','binders.rfid'])->get();
    return $cards;
});

Route::get('/usersJson', function() {
    $user = App\User::with(['roles','roles.permissions'])->get();
    return $user;
});


Route::get('/homeJson', 'HomeController@homeStatus');

Route::resource('/settings','SettingsController');
Route::resource('/classifications','ClassificationsController');


Route::get('/cards','CardsController@index');
Route::get('/cards/assign/{CardID}','CardsController@edit');
Route::post('/cards/{CardID}','CardsController@update');


Route::get('/bind/create/{CardID}','BindersController@create');
Route::post('/bind/{CardID}','BindersController@store');

Route::resource('/cardholders','CardholdersController');

Route::resource('users','UsersController');
Route::resource('roles', 'RolesController');


Route::get('/entries','ReportsController@entries');
Route::get('/generateEntries','ReportsController@generateEntries');
Route::get('/generateEntriesExport','ReportsController@generateEntriesExport');

//Daily Monitoring route setup
Route::get('/monitors/create/{id}','MonitorsController@create');
Route::post('/monitors/{id}', 'MonitorsController@store');
Route::get('/monitors/{monitor}/edit/{id}', ['as' => 'monitors.edit', 'uses' => 'MonitorsController@edit']);
// Route::post('/monitors/{monitor}', ['as' => 'monitors.update', 'uses' => 'MonitorsController@update']);
Route::resource('monitors', 'MonitorsController', ['except' => [
    'create', 'store', 'edit'
]]);


});

Route::any('{any?}', function ($any = null) {
    if (Auth::check()) {
        return redirect('/home');
    } else {
        return redirect('/');
    }
});

