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


Route::get('/', function()
{
    // check the current user
    if (Entrust::hasRole('Hauler')) {
        return redirect('hauler/online/home');
    } 
    
    if (Entrust::hasRole('Pickup')) {
        return redirect('pickups/online');
    }

    if (Entrust::hasRole('Pickup-level-2')) {
        return redirect('monitor/feed');
    }

    if (Entrust::hasRole('Queue-monitoring')) {
        return redirect('monitor/feed');
    }

    return view('home');
    
})->middleware('auth');


// Route::get('/reassignApproval','SmsController@receiveReassign');

Route::get('/barrier','FeedsController@barrier');
Route::get('/barrierApi','FeedsController@barrierApi');
//Route Setup for driver monitoring
Route::get('/driver/queues','LineupsController@DriversQue');
// Route setup to check last dr submission
Route::get('/checkSubmissionDate/{plate_number}','LineupApiController@checkSubmissionDate');
Route::get('/queues','LineupApiController@getDriverQue');
Route::get('/getLastDriver','LineupApiController@getLastDriver');
Route::get('/serving','ServingController@currentlyServing');
Route::get('/servedToday','ServingController@servedToday');
Route::get('/getTotalQueueToday','LineupApiController@getTotalQueueToday');

Auth::routes();

// secure auth
Route::group(['middleware' => 'auth'], function () {
    
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/logs','LogsController@index');
Route::get('/entriesIn','LogsController@entriesIn');
Route::get('/entriesOut','LogsController@entriesOut');

Route::get('/prints','PrintController@index');
Route::get('/forPrint','PrintController@getForPrint');
Route::post('/prints/{id}','PrintController@printed');

Route::resource('/drivers','DriversController');
Route::get('/drivers/disapproved/{driver}','DriversController@disapprovedDriver');
Route::patch('/drivers/disapproved/{driver}',[  'as' => 'disapproved.update' ,'uses' => 'DriversController@disapprovedDriverUpdate']);
Route::get('/drivers/{driver}/reassign','DriversController@reassign');
Route::patch('/reassign/{driver}',[  'as' => 'reassign.update' ,'uses' => 'DriversController@submitReassign']);
Route::get('/exportDrivers','DriversController@exportDrivers');
Route::post('/drivers/deactivate/{id}','DriversController@deactivateRfid');
Route::post('/drivers/activate/{id}','DriversController@activateRfid');
Route::post('/drivers/reverseDisapproved/{id}','DriversController@reverseDisapproved');
Route::get('/drivers/lostCard/{id}','DriversController@lostCardCreate');
Route::patch('/drivers/lostCard/{id}','DriversController@lostCardStore');
Route::get('/drivers/reprint/{driver}/','LostCardController@create');
Route::post('/drivers/reprint/{driver}/','LostCardController@store');
Route::get('/drivers/transfer-hauler/{driver}','DriversController@transferHauler');
Route::patch('/drivers/trasnfer-hauler/{driver}',[  'as' => 'transfer-hauler.update' ,'uses' => 'DriversController@transferHaulerSubmit']);
Route::post('/driver-image','ImagesController@driverImage');
Route::get('/getImage','ImagesController@getImage');

Route::get('/drivers/{driver}/editInfo','DriversController@editInfo');
Route::patch('/drivers/updateInfo/{driver}', ['as' => 'drivers.updateInfo', 'uses' => 'DriversController@updateInfo']);


Route::resource('/trucks','TrucksController');
Route::get('/trucks/{truck}/transfer','TrucksController@transferHauler');
Route::patch('/transfer/{truck}',[  'as' => 'transfer.update' ,'uses' => 'TrucksController@updateTransferHauler']);
Route::get('/exportTrucks','TrucksController@exportTrucks');
Route::post('/trucks/deactivate/{id}','TrucksController@deactivateTruck');
Route::post('/trucks/remove/{id}','TrucksController@removeDriver');
Route::post('/trucks/changePlateNumber/{id}','TrucksController@changePlateNumber');

Route::get('/trucks/{truck}/editInfo','TrucksController@editInfo');
Route::patch('/trucks/updateInfo/{truck}', ['as' => 'trucks.updateInfo', 'uses' => 'TrucksController@updateInfo']);


Route::resource('/haulers','HaulersController');

Route::get('/confirm/create/{id}/{plate}','ConfirmsController@create');
Route::post('/confirm/{id}/{plate}','ConfirmsController@store');
Route::get('/confirm/pending','ConfirmsController@pending');

Route::get('/driversJson','DriversController@driversJson');
Route::get('/noTruckJson','DriversController@noTruckJson');
Route::get('/deactivatedDriversJson','DriversController@deactivatedDriversJson');

// return Json results

Route::get('/appUrl', function() {
    $url = config('app.url');
    return $url;
});

Route::get('/trucksJson', function() {
    $trucks = App\Truck::with(['driver','drivers','haulers','drivers.cardholder','card','hauler'])->orderBy('id','DESC')->get();
    return $trucks;
});

Route::get('/haulersJson', function() {
    $haulers = App\Hauler::select('id','name')->orderBy('id','DESC')->get();

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
Route::get('/users/driver/hauler/{user}','UsersController@userDriverHauler');
Route::get('/users/truck/hauler/{user}','UsersController@userTruckHauler');
Route::get('/users/hauler/online','UsersController@haulerOnline');
Route::resource('roles', 'RolesController');

//Hauler Route Online setup
Route::get('hauler/online/home','HaulerOnlineController@index');
Route::get('hauler/online/create','HaulerOnlineController@haulerDriverCreate');
Route::post('hauler/online/store','HaulerOnlineController@haulerDriverStore');
Route::get('drivers/{driver}/online/reassign','HaulerOnlineController@haulerOnlineReassign');
Route::patch('online/reassign/{driver}',[  'as' => 'online-reassign.update' ,'uses' => 'HaulerOnlineController@haulerOnlineReassignSubmit']);
Route::get('online/users/{user}/edit','HaulerOnlineController@haulerEditUser');
Route::patch('online/users/update/{user}',[  'as' => 'haulerUsers.update' ,'uses' => 'HaulerOnlineController@haulerUpdateUser']);

// Route setup for online pickup
Route::get('/pickups/online','PickupOnlineController@index');
Route::get('/pickups/unserved/{pickup}/edit','PickupOnlineController@editPickup');
Route::patch('/pickups/unserved/{pickup}/update', ['as' => 'pickups-unserved.update', 'uses' => 'PickupOnlineController@updatePickup']);
Route::delete('/pickups/unserved/{pickup}','PickupOnlineController@cancelPickup');

Route::get('/getPickupData','PickupOnlineController@getPickupData');
Route::get('/getPickupWithCardholder','PickupOnlineController@getPickupWithCardholder');

Route::post('/storePickup','PickupOnlineController@storePickup');
Route::patch('pickups/assign/{pickup}',[  'as' => 'pickups-assign.update' ,'uses' => 'PickupsController@assignCardholder']);



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
Route::post('/pickups/deactivate/{id}','PickupsController@deactive');
Route::resource('/pickups','PickupsController');


//Route for pickup monitoring and deliveries monitoring
Route::get('/monitor/feed','QueuesController@index');
Route::get('/monitor/pickups', 'QueuesController@pickups');
Route::get('/monitor/deliveries','QueuesController@deliveries');
Route::post('/storeCurrentlyServing/{id}','ServingController@storeCurrentlyServing');


Route::get('/feed','FeedsController@index');
Route::get('/feed-content','FeedsController@feedContent');
Route::get('/home-content','FeedsController@homeFeed');
// Route::get('/barrier','FeedsController@barrier');
// Route::get('/barrier-content','FeedsController@barrierContent');
// Route::get('/pass-content','FeedsController@driverPass');

//lineups route setup
Route::get('/lineupJson','LineupsController@lineupJson');
Route::get('/markedJson','LineupsController@markedJson');
Route::get('/lineups','LineupsController@index');
Route::get('/generateLineups','LineupsController@generateLineups');
Route::get('/lineups/{log}','LineupsController@create');
Route::post('/lineups/{log}','LineupsController@store');
Route::get('/lineups/approval/{id}','LineupsController@hustlingApproval');
Route::post('/lineups/approval/{id}','LineupsController@hustlingApprovalStore');





// Routes for driver's passes
Route::post('/passes/{driver}/{log}', 'PassesController@store');

//activies logs
Route::get('/activities','ActivitiesController@index');
Route::get('/generateActivities','ActivitiesController@generateActivities');

//handler's mapping route setup
Route::resource('/handlers','handlerMappingsController');
Route::get('/handlerJson','handlerMappingsController@getHandlerJson');

/**
 *  Generate Analytics JSON results
 */

 Route::get('/analytics','AnalyticsController@index');
 Route::get('/driverandtrucks','AnalyticsController@driversVsTrucks');
 Route::get('/detailEntries','AnalyticsController@detailEntries');

 /**
  *
  * Route setup for dates entries for analytics reports 
  *
  */
Route::get('/dailyEntries','AnalyticsController@dailyEntries');
Route::get('/weeklyEntries','AnalyticsController@weeklyEntries');
Route::get('/monthlyEntries','AnalyticsController@monthlyEntries');
Route::get('/topHaulers','AnalyticsController@topHaulers');


/**
 * 
 * Route Setup for pickup list
 * 
 */
Route::get('/picklist/{driver_id}/{log}','PickListsController@pickList');

});

Route::any('{any?}', function ($any = null) {
    if (Auth::check()) {
        return redirect('/home');
    } else {
        return redirect('/');
    }
});

