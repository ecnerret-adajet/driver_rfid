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
Route::get('/drivers/{driver}/reassign','DriversController@reassign');
Route::patch('/reassign/{driver}',[  'as' => 'reassign.update' ,'uses' => 'DriversController@submitReassign']);
Route::get('/exportDrivers','DriversController@exportDrivers');
Route::post('/drivers/deactivate/{id}','DriversController@deactivateRfid');
Route::post('/drivers/activate/{id}','DriversController@activateRfid');


Route::resource('/trucks','TrucksController');
Route::get('/trucks/{truck}/transfer','TrucksController@transferHauler');
Route::patch('/transfer/{truck}',[  'as' => 'transfer.update' ,'uses' => 'TrucksController@updateTransferHauler']);
Route::get('/exportTrucks','TrucksController@exportTrucks');
Route::post('/trucks/deactivate/{id}','TrucksController@deactivateTruck');



Route::resource('/haulers','HaulersController');

Route::get('/confirm/create/{id}/{plate}','ConfirmsController@create');
Route::post('/confirm/{id}/{plate}','ConfirmsController@store');
Route::get('/confirm/pending','ConfirmsController@pending');

Route::get('/driversJson', function () {
    $drivers = App\Driver::with(['haulers','trucks','cardholder','card','cardholder.logs'])->orderBy('id','DESC')->get();
    // $drivers = App\Driver::where('availability',1)->with(['haulers','trucks','cardholder','card','cardholder.logs'])->orderBy('id','DESC')->get();
    return $drivers;
});

// return Json results

Route::get('/trucksJson', function() {
    $trucks = App\Truck::with(['drivers','haulers','drivers.cardholder','card'])->orderBy('id','DESC')->get();
    return $trucks;
});

Route::get('/haulersJson', function() {
    $haulers = App\Hauler::with(['drivers','trucks'])->orderBy('id','DESC')->get();
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

Route::get('/vendorsJson', function() {
    $url = "http://10.96.4.39/trucking/rfc_get_vendor.php";
    $result = file_get_contents($url);
    $data = json_decode($result,true);
    return $data;
});

Route::get('/subvendorJson', function() {
    $url = "http://10.96.4.39/trucking/rfc_get_vendor.php";
    $result = file_get_contents($url);
    $data = json_decode($result,true);
    $collection = collect($data)->where('vendor_number', '!=', '0000002000');
    return $collection;
});



Route::get('/homeJson', 'HomeController@homeStatus');

Route::resource('/settings','SettingsController');
// Route::resource('/classifications','ClassificationsController');


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


Route::get('/monitors/notrip/{date}/{id}','MonitorsController@noTrip');
Route::post('/monitors/notrip/{date}/{id}', 'MonitorsController@storeNoTrip');
Route::get('/monitors/notrip/{monitor}/{id}/edit/',['as' => 'notrip.edit', 'uses' => 'MonitorsController@editNoTrip']);
Route::post('/monitors/notrip/{monitor}',['as'=>'notrip.update','uses'=>'MonitorsController@updateNoTrip']);




Route::get('/monitors/{monitor}/edit/{id}', ['as' => 'monitors.edit', 'uses' => 'MonitorsController@edit']);
// Route::post('/monitors/{monitor}', ['as' => 'monitors.update', 'uses' => 'MonitorsController@update']);
Route::resource('monitors', 'MonitorsController', ['except' => [
    'create', 'store', 'edit'
]]);

Route::get('/pickupsJson','PickupsController@pickupsJson');
Route::get('/pickupsStatus','PickupsController@pickupsStatus');
Route::get('/generatePickups','PickupsController@generatePickups');
Route::resource('/pickups','PickupsController');


Route::get('/feed','FeedsController@index');
Route::get('/feed-content','FeedsController@feedContent');
Route::get('/home-content','FeedsController@homeFeed');
Route::get('/barrier','FeedsController@barrier');
Route::get('/barrier-content','FeedsController@barrierContent');

//queues route setup
Route::get('/queueJson','QueuesController@queueJson');
Route::get('/markedJson','QueuesController@markedJson');
Route::get('/queues','QueuesController@index');
Route::get('/generateQueues','QueuesController@generateQueues');
Route::get('/queues/{log}','QueuesController@create');
Route::post('/queues/{log}','QueuesController@store');



//activies logs
Route::get('/activities','ActivitiesController@index');
Route::get('/generateActivities','ActivitiesController@generateActivities');

//handler's mapping route setup
Route::resource('/handlers','handlerMappingsController');
Route::get('/handlerJson','handlerMappingsController@getHandlerJson');


});

Route::any('{any?}', function ($any = null) {
    if (Auth::check()) {
        return redirect('/home');
    } else {
        return redirect('/');
    }
});

