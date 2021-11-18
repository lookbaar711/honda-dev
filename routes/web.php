<?php
include_once 'web_builder.php';
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

Route::pattern('slug', '[a-z0-9- _]+');

Route::group(['prefix' => 'admin', 'namespace'=>'Admin'], function () {

    # Error pages should be shown without requiring login
    Route::get('404', function () {
        return view('admin/404');
    });
    Route::get('500', function () {
        return view('admin/500');
    });

    # All basic routes defined here
    Route::get('login', 'AuthController@getSignin')->name('login');
    Route::get('signin', 'AuthController@getSignin')->name('signin');
    Route::post('signin', 'AuthController@postSignin')->name('postSignin');
    Route::post('signup', 'AuthController@postSignup')->name('admin.signup');
    Route::post('forgot-password', 'AuthController@postForgotPassword')->name('forgot-password');
    Route::get('login2', function () {
        return view('admin/login2');
    });


    # Register2
    Route::get('register2', function () {
        return view('admin/register2');
    });
    Route::post('register2', 'AuthController@postRegister2')->name('register2');

    # Forgot Password Confirmation
    Route::get('forgot-password/{userId}/{passwordResetCode}', 'AuthController@getForgotPasswordConfirm')->name('forgot-password-confirm');
    Route::post('forgot-password/{userId}/{passwordResetCode}', 'AuthController@getForgotPasswordConfirm');

    # Logout
    Route::get('logout', 'AuthController@getLogout')->name('logout');

    # Account Activation
    Route::get('activate/{userId}/{activationCode}', 'AuthController@getActivate')->name('activate');
});


Route::group(['prefix' => 'admin', 'middleware' => 'admin', 'as' => 'admin.'], function () {
    # Dashboard / Index
    //Route::get('/', 'JoshController@showHome')->name('dashboard');

    //Log viewer routes
    Route::get('log_viewers', '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@index')->name('log-viewers');
    Route::get('log_viewers/logs', '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@listLogs')->name('log_viewers.logs');
    Route::delete('log_viewers/logs/delete', '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@delete')->name('log_viewers.logs.delete');
    Route::get('log_viewers/logs/{date}', '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@show')->name('log_viewers.logs.show');
    Route::get('log_viewers/logs/{date}/download', '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@download')->name('log_viewers.logs.download');
    Route::get('log_viewers/logs/{date}/{level}', '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@showByLevel')->name('log_viewers.logs.filter');
    Route::get('log_viewers/logs/{date}/{level}/search', '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@search')->name('log_viewers.logs.search');
    Route::get('log_viewers/logcheck', '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@logCheck')->name('log-viewers.logcheck');
    //end Log viewer
    # Activity log
    Route::get('activity_log/data', 'JoshController@activityLogData')->name('activity_log.data');
//    Route::get('/', 'JoshController@index')->name('index');
});

Route::group(['prefix' => 'admin','namespace'=>'Admin', 'middleware' => 'admin', 'as' => 'admin.'], function () {

    # User Management
    Route::group([ 'prefix' => 'users'], function () {
        Route::get('data', 'UsersController@data')->name('users.data');
        Route::post('data', 'UsersController@data')->name('users.postdata');

        Route::get('{user}/delete', 'UsersController@destroy')->name('users.delete');
        Route::get('{user}/confirm-delete', 'UsersController@getModalDelete')->name('users.confirm-delete');

        Route::get('{user}/restore', 'UsersController@getRestore')->name('users.restore');
        Route::get('{user}/confirm-restore', 'UsersController@getModalRestore')->name('users.confirm-restore');

        Route::get('{user}/change', 'UsersController@change')->name('users.change');
        Route::post('{user}/change-password', 'UsersController@changePassword')->name('users.change-password');

        //Route::post('{user}/passwordreset', 'UsersController@passwordreset')->name('passwordreset');
        Route::post('passwordreset', 'UsersController@passwordreset')->name('passwordreset');
    });
    Route::resource('users', 'UsersController');

    //Route::get('deleted_users',['before' => 'Sentinel', 'uses' => 'UsersController@getDeletedUsers'])->name('deleted_users');
    //Route::get('restored_users',['before' => 'Sentinel', 'uses' => 'UsersController@getRestoredUsers'])->name('restored_users');


    # Event Management
    Route::group(['prefix' => 'events'], function () {
        Route::get('data', 'EventsController@data')->name('events.data');
        Route::post('data', 'EventsController@data')->name('events.postdata');

        Route::get('{event}/delete', 'EventsController@destroy')->name('events.delete');
        Route::get('{event}/confirm-delete', 'EventsController@getModalDelete')->name('events.confirm-delete');
    });
    Route::resource('events', 'EventsController');

    # Dealer Management
    Route::group(['prefix' => 'dealers'], function () {
        Route::get('data', 'DealersController@data')->name('dealers.data');
        Route::post('data', 'DealersController@data')->name('dealers.postdata');

        Route::get('{dealer}/delete', 'DealersController@destroy')->name('dealers.delete');
        Route::get('{dealer}/confirm-delete', 'DealersController@getModalDelete')->name('dealers.confirm-delete');



        Route::get('download', 'DealersController@download')->name('dealers.download');
        Route::get('import', 'DealersController@import')->name('dealers.import');
    });
    Route::resource('dealers', 'DealersController');
    Route::post('/dealers/dataExcel', 'DealersController@dataExcel');
    Route::post('/dealers/SentDataExcel', 'DealersController@SentDataExcel');
    Route::get('/dealers/downloadfile/{type}', 'DealersController@downloadfile');

    Route::post('/saledealers/dataExcel', 'SaleDealersController@dataExcel');
    Route::post('/saledealers/SentDataExcel', 'SaleDealersController@SentDataExcel');

    //update dealers
    Route::post('dealers/{dealer}/update', 'DealersController@update')->name('dealers.update');

    //get select area (dealer)
    Route::post('/dealer_area', 'DealersController@getArea')->name('dealers.getarea');
    //get select zone
    Route::post('/sale_dealer_zone', 'SaleDealersController@getZone')->name('sale_dealers.getzone');
    //get select area (sale_dealer)
    Route::post('/sale_dealer_area', 'SaleDealersController@getArea')->name('sale_dealers.getarea');

    // InsertSaleDealer
    Route::post('/InsertSaleDealer', 'SaleDealersController@InsertSaleDealer');


    # Dealer Detail Management
    Route::group(['prefix' => 'dealer_details'], function () {
        Route::get('data', 'DealerDetailsController@data')->name('dealer_details.data');
        Route::post('data', 'DealerDetailsController@data')->name('dealer_details.postdata');
    });
    Route::resource('dealer_details', 'DealerDetailsController');

    //update dealer_detail
    Route::post('dealer_details/{dealer_detail}/update', 'DealerDetailsController@update')->name('dealer_details.update');


    # Sale Dealer Management
    Route::group(['prefix' => 'sale_dealers'], function () {
        Route::get('data', 'SaleDealersController@data')->name('sale_dealers.data');
        Route::post('data', 'SaleDealersController@data')->name('sale_dealers.postdata');

        Route::get('{sale_dealer}/delete', 'SaleDealersController@destroy')->name('sale_dealers.delete');
        Route::get('{sale_dealer}/confirm-delete', 'SaleDealersController@getModalDelete')->name('sale_dealers.confirm-delete');


        Route::get('download', 'SaleDealersController@download')->name('sale_dealers.download');
        Route::get('import', 'SaleDealersController@import')->name('sale_dealers.import');
    });
    Route::resource('sale_dealers', 'SaleDealersController');

    //update sale_dealers
    Route::post('sale_dealers/{sale_dealer}/update', 'SaleDealersController@update')->name('sale_dealers.update');


    # Check-in / Check-out Managment
    Route::group(['prefix' => 'checkin_checkout'], function () {
        Route::get('data', 'CheckInCheckOutController@data')->name('checkin_checkout.data');
        Route::post('data', 'CheckInCheckOutController@data')->name('checkin_checkout.postdata');

        Route::get('checkin', 'CheckInCheckOutController@checkin')->name('checkin_checkout.checkin');
        Route::post('checkin_data', 'CheckInCheckOutController@getCheckIndata')->name('checkin_checkout.post_checkin_data');

        Route::get('checkout', 'CheckInCheckOutController@checkout')->name('checkin_checkout.checkout');
        Route::post('checkout_data', 'CheckInCheckOutController@getCheckOutdata')->name('checkin_checkout.post_checkout_data');

    });
    Route::resource('checkin_checkout', 'CheckInCheckOutController');

    //Route::get('set_checkin', 'CheckInCheckOutController@setCheckIn')->name('set_checkin');
    Route::post('set_checkin', 'CheckInCheckOutController@setCheckIn')->name('set_checkin');
    Route::post('set_checkout', 'CheckInCheckOutController@setCheckOut')->name('set_checkout');


    Route::group(['prefix' => 'reports'], function (){
        Route::get('/', [
            'as'    => 'admin.reports.index.get',
            'uses'  => 'ReportsController@getCheckinCheckout'
        ]);
    });


    # Preemption Managment
    Route::group(['prefix' => 'preemptions'], function (){
        Route::get('/', [
            'as'    => 'admin.preemptions.index.get',
            'uses'  => 'PreemptionsController@getIndex'
        ]);
        // Route::get('setting_preemptions', [
        //     'as'    => 'admin.preemptions.setting_preemptions.get',
        //     'uses'  => 'PreemptionsController@getSettingPreemption'
        // ]); getSettingPreemptionData UpdatePreemptionData getSaleAndPre
        Route::get('setting_preemptions/{event}', 'PreemptionsController@getSettingPreemption');
        Route::post('getPreAll/', 'PreemptionsController@getPreAll');
        Route::get('expose_preemptions/{event}', 'PreemptionsController@getExposePreemption');
        Route::get('return_preemptions/{event}', 'PreemptionsController@getReturnPreemption');
        Route::post('getSale', 'PreemptionsController@getSale');
        Route::post('UpdatePreemption', 'PreemptionsController@UpdatePreemptionData');
        Route::post('getSaleAndPre', 'PreemptionsController@getSaleAndPre');
        //  Route::get('expose_preemptions', [
        //     'as'    => 'admin.preemptions.expose_preemptions.get',
        //     'uses'  => 'PreemptionsController@getExposePreemption'
        // ]);
        // Route::get('setting_preemptions/{event}', 'PreemptionsController@getSettingPreemption');
        //   Route::get('return_preemptions', [
        //     'as'    => 'admin.preemptions.return_preemptions.get',
        //     'uses'  => 'PreemptionsController@getReturnPreemption'
        // ]);

        Route::post('get_sub_model', 'PreemptionsController@getSubModel')->name('preemptions.getsubmodel');
    });

    Route::get('preemptions/getSettingPreemptionData/{event}', 'PreemptionsController@getSettingPreemptionData');
    Route::post('preemptions/InsertSettingPreemption', 'PreemptionsController@InsertSettingPreemption');
    // InsertSettingPreemption

    Route::get('events/{event}/dealers', 'DealersController@dealerIndex')->name('dealers.dealer_index');
    Route::get('events/{event}/dealers/{dealer}/dealer_details', 'DealerDetailsController@dealerDetailIndex')->name('dealer_details.dealer_detail_index');

    Route::get('events/{event}/sale_dealers', 'SaleDealersController@saleDealerIndex')->name('sale_dealers.sale_dealer_index');

    Route::get('events/{event}/checkin_checkout', 'CheckInCheckOutController@checkInCheckOutIndex')->name('checkin_checkout.checkin_checkout_index');

    Route::get('events/{event}/preemptions', 'PreemptionsController@preemptionIndex')->name('preemptions.preemption_index');

    Route::get('events/{event}/reports', 'ReportsController@reportIndex')->name('reports.report_index');

     # Report Managment
    Route::group(['prefix' => 'reports'], function () {
        Route::post('DataSaleDealerReport', 'ReportsController@DataSaleDealerReport');
        Route::post('DataCheckinCheckoutReport', 'ReportsController@DataCheckinCheckoutReport');
        Route::post('DataPreemptionReport', 'ReportsController@DataPreemptionReport');
        Route::post('DataDealerCheckinReport', 'ReportsController@DataDealerCheckinReport');

        // Get select sub model car
        Route::post('getSubModelCar', 'ReportsController@getSubModelCar')->name('reports.getSubModelCar');

        // Get form option dealer, zone, area
        Route::post('getDlr', 'ReportsController@getDlr')->name('reports.getDlr');
        Route::post('getZone', 'ReportsController@getZone')->name('reports.getZone');
        Route::post('getArea', 'ReportsController@getArea')->name('reports.getArea');

        Route::post('ExportExcel', 'ReportsController@ExportExcel')->name('reports.ExportExcel');

    });
    Route::resource('reports', 'ReportsController');


    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::post('/dashboard', 'DashboardController@showHome')->name('search_dashboard');

    //get select event
    Route::post('/dashboard/dashboard_event', 'DashboardController@getEvent')->name('dashboard.getevent');
    //get select dlr
    Route::post('/dashboard/dashboard_dlr', 'DashboardController@getDLR')->name('dashboard.getdlr');
    //get select zone
    Route::post('/dashboard/dashboard_zone', 'DashboardController@getZone')->name('dashboard.getzone');
    //get select area (sale_dealer)
    Route::post('/dashboard/dashboard_area', 'DashboardController@getArea')->name('dashboard.getarea');


    Route::get('/ManagementModalAndType/show', 'ManagementModelCarController@index');
    Route::post('/ManagementModalAndType/InsertModelType', 'ManagementModelCarController@InsertModelType');
    Route::post('/ManagementModalAndType/ShowModelType', 'ManagementModelCarController@ShowModelType');
    Route::post('/ManagementModalAndType/GetModelType', 'ManagementModelCarController@GetModelType');
    Route::post('/ManagementModalAndType/UpdateModelType', 'ManagementModelCarController@UpdateModelType');



});



# Remaining pages will be called from below controller method
# in real world scenario, you may be required to define all routes manually

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::get('{name?}', 'JoshController@showView');
});

#frontend views
Route::get('/', ['as' => 'home', function () {
    return view('admin.login');
}]);

Route::get('{name?}', 'FrontEndController@showFrontEndView');
# End of frontend views
