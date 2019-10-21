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
    // List of roles
    // 1 = Administrator
    // 2 = Monitoring
    // 3 = Personnel
    // 4 = Approver
    // 5 = Hauler
    // 6 = Pickup
    // 7 = Pickup-level-2
    // 8 = Queue-monitoring
    // 9 = spc-monitoring

    //Get user current role
    $user_role = Auth::user()->roles->first()->id;
    switch ($user_role) {
        case 5: // Hauler
        return redirect('hauler/online/home');
        break;
        case 6: // Pickup
        return redirect('pickups/online');
        break;
        case 7: // Pickup-level-2
        return redirect('queueEntryFeed');
        break;
        case 8: // Queue Monitoring
        return redirect('queueEntryFeed');
        break;
        case 9: // Spc/QC Monitoring
        return redirect('trucks');
        break;
        default:
        return view('home');
        break;
    }

})->middleware('auth');

/**
* Guest Routes
*/

// Route::get('/reassignApproval','SmsController@receiveReassign');

Route::get('/getTotalTrucksInPlant','FeedsController@getTotalTrucksInPlant');

Route::get('/laPazAPI','BarriersController@laPazAPI');
Route::get('/lapaz','BarriersController@laPazArea');
Route::get('/manilaAPI','BarriersController@manilaAPI');
Route::get('/manila','BarriersController@manilaArea');
Route::get('/bataanAPI','BarriersController@bataanAPI');
Route::get('/bataan','BarriersController@bataanArea');

//Route Setup for driver monitoring
Route::get('/driver/queues','LineupsController@DriversQue');
// Route setup to check last dr submission
Route::get('/checkSubmissionDate/{plate_number}','LineupApiController@checkSubmissionDate');
Route::get('/queues','LineupApiController@getDriverQue');
Route::get('/getLastDriver','LineupApiController@getLastDriver');
Route::get('/conditionFromLastDriver/{driverqueue}','LineupApiController@conditionFromLastDriver');
Route::get('/serving/{driverqueue}','ShipmentsController@currentlyServing');
// Route::get('/serving/{driverqueue}','ServingController@currentlyServing');
// Route::get('/servedToday/{driverqueue}','ServingController@servedToday');
Route::get('/servedToday/{driverqueue}','ShipmentsController@servedToday');
Route::get('/getTotalQueueToday','LineupApiController@getTotalQueueToday');

// BTN Route for driver queueing monitor
Route::get('/driver/queuesBtn','LineupsController@DriversQueBtn');
Route::get('/queuesBtn','LineupApiController@getBtnDriverQue');
Route::get('/getTotalQueueTodayBtn','LineupApiController@getBtnTotalQueueToday');
Route::get('/getLastDriverBtn','LineupApiController@getBtnLastDriver');

/**
*  Gate Entries Route Setup w/ Realtime pusher
*/
Route::post('/storeGateEntries/{driverqueue_id}','GateEntriesController@storeGateEntries');
Route::get('/getLastGateEntry/{driverqueue_id}','GateEntriesController@getLastGateEntry');
Route::get('/gateEntry/{driverqueue}','GateEntriesController@gateEntry');
Route::get('/getGateEntries/{driverqueue_id}/{date}','GateEntriesController@getGateEntries');

/**
* Queue Entries Route Setup
*/
Route::post('/storeQueueEntries/{driverqueue_id}','QueueEntriesController@storeQueueEntries');
Route::get('/getQueueEntries/{driverqueue_id}','QueueEntriesController@getQueueEntries');
Route::get('/queueEntry/{driverqueue}','QueueEntriesController@queueEntry');

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

/**
*  Authentication Route
*/
Route::group(['middleware' => 'auth'], function () {

    //Test Endpoint
    Route::get('/processGateEntries','GateEntriesController@processGateEntries');
    Route::get('/shipmentAssigned','ShipmentsController@shipmentAssigned');

    /**
    * Queue Entries Setting Route
    */
    Route::get('/getQueueEntriesFeed/{driverqueue_id}','QueueEntriesController@getQueueEntriesFeed');
    Route::get('/getQueueFromCreatedDate/{driverqueue_id}','QueueEntriesController@getQueueFromCreatedDate');
    Route::get('/expiredQueues/{driverqueue_id}','QueueEntriesController@expiredQueues');
    Route::get('/queueEntryFeed','QueueEntriesController@queueEntryFeed');
    Route::get('/queueFeed','QueueEntriesController@queueFeed'); // newly added
    Route::get('/searchQueueEntriesFeed/{driverqueue}','QueueEntriesController@searchQueueEntriesFeed');
    Route::get('/getQueueStatus/{driverqueue}','QueueEntriesController@getQueueStatus');
    Route::get('/lastDriverTapped/{driverqueue}','QueueEntriesController@lastDriverTapped');
    Route::get('/getQueueEntriesJson/{driverqueue_id}/{date}','QueueEntriesController@getQueueEntriesJson');

    /**
    * Entry Report Route
    */
    Route::get('/exportEntries/{driverqueue_id}/{date}','EntryReportController@exportEntries');

    /**
    * Statuses Count Route
    */
    Route::get('/totalTrucksInPlant','StatusesController@totalTrucksInPlant');
    Route::get('/totalAssignedShipment','StatusesController@totalAssignedShipment');
    Route::get('/totalForPrint','StatusesController@totalForPrint');
    Route::get('/totalPickup','StatusesController@totalPickup');


    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/logs','LogsController@index');
    Route::get('/entriesIn','LogsController@entriesIn');
    Route::get('/entriesOut','LogsController@entriesOut');

    Route::get('/prints','PrintController@index');
    Route::get('/forPrint','PrintController@getForPrint');
    Route::post('/prints/{id}','PrintController@printed');

    Route::resource('/drivers','DriversController');
    /**
    * Driver Json Details
    */
    Route::get('driverLogs/{driver}','DriverLogsController@driverLogs');
    Route::get('searchDriverLogs/{driver}/{date}','DriverLogsController@searchDriverLogs');
    Route::get('driverShipments/{driver}','DriverLogsController@driverShipments');
    Route::get('versionObject/{driver}','DriverLogsController@versionObject');


    Route::post('/drivers/restore/{id}','DriversController@restore');
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
    Route::post('/trucks/activate/{id}','TrucksController@activateTruck');
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
    Route::get('/resignedDriversJson','DriversController@resignedDriversJson');

    // return Json results

    Route::get('/trucksJson','TrucksController@trucksJson');
    Route::get('/noDriverJson','TrucksController@noDriverJson');
    Route::get('/deactivatedTrucksJson','TrucksController@deactivatedTrucksJson');

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

    // for monitor feed section pickups
    Route::get('/pickupFeed','PickupsController@pickupFeed');
    Route::get('/pickupInPlant','PickupsController@pickupInPlant');
    Route::get('/unserved','PickupsController@unserved');
    Route::get('/served','PickupsController@served');
    Route::get('/generatePickupFeed','PickupsController@generatePickupFeed');

    // For Sales Monitoring Feed Pickup section
    Route::get('/getPickupData','PickupOnlineController@getPickupData');
    Route::get('/getPickupWithCardholder','PickupOnlineController@getPickupWithCardholder');
    Route::get('/pickupCount','PickupOnlineController@pickupCount');
    Route::get('/pickupServedSearch','PickupOnlineController@pickupServedSearch');
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

        // New Pickup Entries
        Route::get('/pickups-entries','PickupsController@pickupEntries');
        Route::get('/pickupsJson','PickupsController@pickupsJson');
        Route::get('/pickupsStatus','PickupsController@pickupsStatus');
        Route::get('/generatePickups','PickupsController@generatePickups');
        Route::post('/pickups/deactivate/{id}','PickupsController@deactive');
        Route::resource('/pickups','PickupsController');




        //Route for pickup monitoring and deliveries monitoring
        Route::get('/monitor/feed','QueuesController@index');
        Route::get('/monitor/pickups', 'QueuesController@pickups');
        Route::get('/monitor/deliveries','QueuesController@deliveries');
        Route::get('/monitor/assignedShipment','QueuesController@assignedShipment');
        Route::get('/monitor/openShipment','QueuesController@openShipment');
        Route::get('/monitor/count','QueuesController@getDeliveriesCount');
        // Queue Monitoring Bataan
        Route::get('/monitor/btnDeliveries','QueuesController@btnDeliveries');
        Route::get('/monitor/btnAssignedShipment','QueuesController@btnAssignedShipment');
        Route::get('/monitor/btnOpenShipment','QueuesController@btnOpenShipment');
        Route::get('/monitor/btnCount','QueuesController@btnGetDeliveriesCount');
        // Queue Monitoring Lapaz
        Route::get('/monitor/lpzDeliveries','QueuesController@lpzDeliveries');
        Route::get('/monitor/lpzAssignedShipment','QueuesController@lpzAssignedShipment');
        Route::get('/monitor/lpzOpenShipment','QueuesController@lpzOpenShipment');
        Route::get('/monitor/lpzCount','QueuesController@getLpzDeliveriesCount');

        Route::post('/storeCurrentlyServing/{driver_id}/{log_id}','ServingController@storeCurrentlyServing');

        Route::get('/feed','FeedsController@index');
        Route::get('/feed-content','FeedsController@feedContent');
        // Route::get('/home-content','FeedsController@homeFeed');
        Route::get('/homeFeed','FeedsController@homeFeed');
        Route::get('/generateHomeFeed','FeedsController@generateHomeFeed');
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
        * Route Setup for Truck Inspection Deactivation / Activation
        */
        Route::get('/inspects/deactivate/{truck}','TruckInspectionController@deactivateTruckCreate');
        Route::post('/inspects/deactivate/{truck}','TruckInspectionController@deactivateTruckStore');
        Route::get('/inspects/activate/{truck}','TruckInspectionController@activateTruckCreate');
        Route::post('/inspects/activate/{truck}','TruckInspectionController@activateTruckStore');
        Route::get('/inspects/show/{truck}','TruckInspectionController@inspectionHistory');

        /**
        * Route Setup for pickup list
        */
        Route::get('/picklist/{driver_id}/{log}','PickListsController@pickList');
        Route::get('/picklistEntry/{driver_id}','PickListsController@picklistEntry');

        Route::get('/gates/create','MonitoringsController@createGate');
        Route::post('/gates/store','MonitoringsController@storeGate');
        Route::get('/queues/create','MonitoringsController@createQueue');
        Route::post('/queues/store','MonitoringsController@storeQueue');
        // Route::get('/monitoring','MonitoringsController@index');
        Route::get('/gate/entries/{gate}','MonitoringsController@gateEntries');
        Route::get('/queue/entries/{driverqueue}','MonitoringsController@queueEntries');
        Route::get('/gates','MonitoringsController@allGates');
        Route::get('/searchGateEntries/{gate}','MonitoringsController@searchGateEntries');
        Route::get('/searchQueueEntries/{driverqueue}','MonitoringsController@searchQueueEntries');
        Route::get('/driverqueues','MonitoringsController@allQueues');
        Route::get('/exportQueues/{driverqueue}/{date}','MonitoringsController@exportQueues');
        Route::get('/exportGate/{gate}/{date}','MonitoringsController@exportGate');
        Route::resource('/areas', 'AreasController');

        /**
        * Autoshipment End
        */
        Route::get('/autoShipmentEnd','QueuesController@autoShipmentEnd');

        /**
         * Route setup for Export Entries Controller
         */
        Route::get('/exportEntries/report','EntryReportController@viewDisplayEntries');
        Route::get('/displayEntries/{driverqueue_id}/{start_date}/{end_date}','EntryReportController@displayEntries');
        Route::get('/displayEntriesReport/{driverqueue_id}/{date}','EntryReportController@displayEntriesReport');

        /**
         *  Assign RFID to truck
         */
        Route::get('truck-rfid','TrucksController@truckRfid');
        Route::post('assign-rfid/{truck}','TrucksController@assignRFID');

        /*
         * Route setup for Replacements RFID Card
         */
        Route::get('/replacements','ReplacementsController@index');
        Route::get('api-replacements','Api\ReplacementApiController@index');
        Route::post('api-replacements','Api\ReplacementApiController@store');
        Route::patch('api-replacements-approve/{replacement}','Api\ReplacementApiController@forApprovalReplacements');
        Route::get('api-driver-rfid','Api\ReplacementApiController@driverRfidList');
        Route::get('api-reason-replacement','Api\ReplacementApiController@reasonReplacement');
        Route::get('api-replacements-approved','Api\ReplacementApiController@approvedReplacements');
        Route::get('api-replacements-report/{date_from}/{date_to}','Api\ReplacementApiController@replacementReport');


    });


    Route::any('{any?}', function ($any = null) {
        if (Auth::check()) {
            return redirect('/home');
        } else {
            return redirect('/');
        }
    });

