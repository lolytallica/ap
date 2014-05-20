<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Register all the admin routes.
|
*/

Route::group(array('prefix' => 'admin'), function()
{



    # Partner Invoice Management
    Route::group(array('prefix' => 'partnerinvoice'), function()
    {
        Route::get('/', array('as' => 'partnerinvoice', 'uses' => 'Controllers\Admin\PartnerinvoiceController@getIndex'));
        Route::get('create', array('as' => 'create/partnerinvoice', 'uses' => 'Controllers\Admin\PartnerinvoiceController@getCreate'));
        Route::post('create', 'Controllers\Admin\PartnerinvoiceController@postCreate');
        Route::get('{invoiceId}/edit', array('as' => 'update/partnerinvoice', 'uses' => 'Controllers\Admin\PartnerinvoiceController@getEdit'));
        Route::post('{invoiceId}/edit', 'Controllers\Admin\PartnerinvoiceController@postEdit');
        Route::get('{invoiceId}/delete', array('as' => 'delete/partnerinvoice', 'uses' => 'Controllers\Admin\PartnerinvoiceController@getDelete'));


    });

    # Merchant Agreement Invoice Management
    Route::group(array('prefix' => 'mainvoice'), function()
    {
        Route::get('/', array('as' => 'mainvoice', 'uses' => 'Controllers\Admin\MainvoiceController@getIndex'));
        Route::get('create', array('as' => 'create/mainvoice', 'uses' => 'Controllers\Admin\MainvoiceController@getCreate'))->before('hasAccess:manage_ma_invoices');
        Route::post('create', 'Controllers\Admin\MainvoiceController@postCreate');
        Route::get('{invoiceId}/show', array('as' => 'show/mainvoice',  'uses' => 'Controllers\Admin\MainvoiceController@getShow'))->before('hasAccess:view_ma_invoices');
        Route::get('{invoiceId}/approve', array('as' => 'approve/mainvoice', 'uses' => 'Controllers\Admin\MainvoiceController@approve'))->before('hasAccess:manage_ma_invoices');
        Route::get('{invoiceId}/draft', array('as' => 'draft/mainvoice',  'uses' => 'Controllers\Admin\MainvoiceController@draft'))->before('hasAccess:manage_ma_invoices');
        Route::get('{invoiceId}/invoicepdf', array('as' => 'invoicepdf/mainvoice', 'uses' => 'Controllers\Admin\MainvoiceController@getPDF'))->before('hasAccess:view_ma_invoices');

        Route::get('{invoiceId}/edit', array('as' => 'update/mainvoice', 'uses' => 'Controllers\Admin\MainvoiceController@getEdit'))->before('hasAccess:manage_ma_invoices');


        Route::get('{invoiceId}/pdf', array('as' => 'pdf/mainvoice', 'uses' => 'Controllers\Admin\MainvoiceController@getPDF'))->before('hasAccess:manage_ma_invoices');

       // Route::get('status/{status?}', array('uses' => 'Controllers\Admin\MainvoiceController@getIndex', 'as' => 'invoices.index'));

        Route::get('{statusId}/status', array('as' => 'status/mainvoice',  'uses' => 'Controllers\Admin\MainvoiceController@getStatusinvoices'))->before('hasAccess:view_ma_invoices');


        Route::post('{invoiceId}/edit', 'Controllers\Admin\MainvoiceController@postEdit')->before('hasAccess:manage_ma_invoices');

        Route::get('{invoiceId}/pay', array('as' => 'pay/mainvoice', 'uses' => 'Controllers\Admin\MainvoiceController@getPay'))->before('hasAccess:manage_ma_payments');
        Route::post('{invoiceId}/pay', 'Controllers\Admin\MainvoiceController@postPay')->before('hasAccess:manage_ma_payments');

        Route::get('generate', array('as' => 'generate', 'uses' => 'Controllers\Admin\MainvoiceController@generate'))->before('hasAccess:manage_ma_invoices');

    });

    # Merchant Agreement Invoice Payment
    Route::group(array('prefix' => 'mapayment'), function()
    {
        Route::get('/', array('as' => 'mapayment', 'uses' => 'Controllers\Admin\MapaymentController@getIndex'))->before('hasAccess:view_ma_payments');


        Route::get('{paymentId}/show', array('as' => 'show/mapayment',  'uses' => 'Controllers\Admin\MapaymentController@getShow'))->before('hasAccess:manage_ma_invoices');

        Route::get('{paymentId}/edit', array('as' => 'update/mapayment',  'uses' => 'Controllers\Admin\MapaymentController@getEdit'))->before('hasAccess:manage_ma_payments');
        Route::post('{paymentId}/edit', 'Controllers\Admin\MapaymentController@postEdit')->before('hasAccess:manage_ma_payments');

        Route::get('{paymentId}/pdf', array('as' => 'pdf/mapayment',  'uses' => 'Controllers\Admin\MapaymentController@getPDF'))->before('hasAccess:manage_ma_payments');

        Route::get('{invoiceId}/pay', array('as' => 'pay/mapayment',  'uses' => 'Controllers\Admin\MapaymentController@getPay'))->before('hasAccess:manage_ma_payments');
        Route::post('{invoiceId}/pay', 'Controllers\Admin\MapaymentController@postPay')->before('hasAccess:manage_ma_payments');


    });

    # Reports Management
    Route::group(array('prefix' => 'reports'), function()
    {


        Route::get('/', array('as' => 'reports', 'uses' => 'Controllers\Admin\ReportController@getIndex'))->before('hasAccess:manage_reports');
      /*  Route::get('/', array('as' => 'reports', function(){
            return Redirect::to('reports/searchreport');
        }))->before('hasAccess:manage_reports');*/




        Route::get('create', array('as' => 'create/report', 'uses' => 'Controllers\Admin\ReportController@getCreate'))->before('hasAccess:manage_reports');
        Route::post('create', 'Controllers\Admin\ReportController@postCreate')->before('hasAccess:manage_reports');

        Route::get('searchreport', array('as' => 'search', 'uses' => 'Controllers\Admin\ReportController@getIndex'))->before('hasAccess:manage_reports');
        Route::post('searchreport', 'Controllers\Admin\ReportController@postSearchreport')->before('hasAccess:manage_reports');


        Route::get('{rsId}/searchreport', array('as' => 'search/reports',  'uses' => 'Controllers\Admin\ReportController@getIndex'))->before('hasAccess:manage_reports');
        Route::post('{rsId}/searchreport', 'Controllers\Admin\ReportController@postSearchreport')->before('hasAccess:manage_reports');

        Route::post('savereportsearch', 'Controllers\Admin\ReportController@saveReportsearch')->before('hasAccess:manage_reports');

        Route::get('{traceId}/trace', array('as' => 'trace', 'uses' => 'Controllers\Admin\ReportController@getTrace'))->before('hasAccess:manage_reports');
        Route::get('redemptions', array('as' => 'redemptions', 'uses' => 'Controllers\Admin\ReportController@searchRedemptions'))->before('hasAccess:manage_reports');



    });

    # Partner Agreement Invoice Payment


    # Merchant Agreement Management
    Route::group(array('prefix' => 'merchantagreement'), function()
    {
        Route::get('/', array('as' => 'merchantagreement', 'uses' => 'Controllers\Admin\MerchantagreementController@getIndex'))->before('hasAccess:merchantagreement');
        Route::get('{maId}/show', array('as' => 'show/ma', 'uses' => 'Controllers\Admin\MerchantagreementController@getShow'))->before('hasAccess:merchantagreement');
        Route::get('create', array('as' => 'create/ma', 'uses' => 'Controllers\Admin\MerchantagreementController@getCreate'))->before('hasAccess:merchantagreement');
        Route::post('create', 'Controllers\Admin\MerchantagreementController@postCreate')->before('hasAccess:merchantagreement');
        Route::get('{maId}/edit', array('as' => 'update/ma', 'uses' => 'Controllers\Admin\MerchantagreementController@getEdit'));
        Route::post('{maId}/edit', 'Controllers\Admin\MerchantagreementController@postEdit')->before('hasAccess:merchantagreement');
        Route::get('{maId}/delete', array('as' => 'delete/ma', 'uses' => 'Controllers\Admin\MerchantagreementController@getDelete'))->before('hasAccess:merchantagreement');

        Route::get('{maId}/statushistorical', array('as' => 'statushistorical', 'uses' => 'Controllers\Admin\MerchantagreementController@getStatusHistorical'))->before('hasAccess:merchantagreement');


    });


    # Merchants  Management
    Route::group(array('prefix' => 'merchant'), function()
    {
        Route::get('/', array('as' => 'merchant', 'uses' => 'Controllers\Admin\MerchantController@getIndex'))->before('hasAccess:merchant');
        Route::get('{mId}/show', array('as' => 'show/merchant', 'uses' => 'Controllers\Admin\MerchantController@getShow'))->before('hasAccess:merchant');
        Route::get('create', array('as' => 'create/merchant', 'uses' => 'Controllers\Admin\MerchantController@getCreate'))->before('hasAccess:merchant');
        Route::post('create', 'Controllers\Admin\MerchantController@postCreate')->before('hasAccess:merchant');
        Route::get('{mId}/edit', array('as' => 'update/merchant', 'uses' => 'Controllers\Admin\MerchantController@getEdit'))->before('hasAccess:merchant');
        Route::post('{mId}/edit', 'Controllers\Admin\MerchantController@postEdit')->before('hasAccess:merchant');
        Route::get('{mId}/delete', array('as' => 'delete/merchant', 'uses' => 'Controllers\Admin\MerchantController@getDelete'))->before('hasAccess:merchant');

        Route::get('{mId}/statushistorical', array('as' => 'merchantstatushistorical', 'uses' => 'Controllers\Admin\MerchantController@getStatusHistorical'))->before('hasAccess:merchant');


    });


    # User Management
	Route::group(array('prefix' => 'users'), function()
	{
		Route::get('/', array('as' => 'users', 'uses' => 'Controllers\Admin\UsersController@getIndex'));
		Route::get('create', array('as' => 'create/user', 'uses' => 'Controllers\Admin\UsersController@getCreate'));
		Route::post('create', 'Controllers\Admin\UsersController@postCreate');
		Route::get('{userId}/edit', array('as' => 'update/user', 'uses' => 'Controllers\Admin\UsersController@getEdit'));
		Route::post('{userId}/edit', 'Controllers\Admin\UsersController@postEdit');
		Route::get('{userId}/delete', array('as' => 'delete/user', 'uses' => 'Controllers\Admin\UsersController@getDelete'));
		Route::get('{userId}/restore', array('as' => 'restore/user', 'uses' => 'Controllers\Admin\UsersController@getRestore'));
        Route::get('{userId}/select_groups', array('as' => 'user/select_groups', 'uses' => 'Controllers\Admin\UsersController@selectGroups'));
	});

	# Group Management
	Route::group(array('prefix' => 'groups'), function()
	{
		Route::get('/', array('as' => 'groups', 'uses' => 'Controllers\Admin\GroupsController@getIndex'));
		Route::get('create', array('as' => 'create/group', 'uses' => 'Controllers\Admin\GroupsController@getCreate'));
		Route::post('create', 'Controllers\Admin\GroupsController@postCreate');
		Route::get('{groupId}/edit', array('as' => 'update/group', 'uses' => 'Controllers\Admin\GroupsController@getEdit'));
		Route::post('{groupId}/edit', 'Controllers\Admin\GroupsController@postEdit');
		Route::get('{groupId}/delete', array('as' => 'delete/group', 'uses' => 'Controllers\Admin\GroupsController@getDelete'));
		Route::get('{groupId}/restore', array('as' => 'restore/group', 'uses' => 'Controllers\Admin\GroupsController@getRestore'));
	});

	# Dashboard
	Route::get('/', array('as' => 'admin', 'uses' => 'Controllers\Admin\DashboardController@getIndex'));

});

/*
|--------------------------------------------------------------------------
| Authentication and Authorization Routes
|--------------------------------------------------------------------------
|
|
|
*/

Route::group(array('prefix' => 'auth'), function()
{

	# Login
	Route::get('signin', array('as' => 'signin', 'uses' => 'AuthController@getSignin'));
	Route::post('signin', 'AuthController@postSignin');

	# Register
	Route::get('signup', array('as' => 'signup', 'uses' => 'AuthController@getSignup'));
	Route::post('signup', 'AuthController@postSignup');

	# Account Activation
	Route::get('activate/{activationCode}', array('as' => 'activate', 'uses' => 'AuthController@getActivate'));

	# Forgot Password
	Route::get('forgot-password', array('as' => 'forgot-password', 'uses' => 'AuthController@getForgotPassword'));
	Route::post('forgot-password', 'AuthController@postForgotPassword');

	# Forgot Password Confirmation
	Route::get('forgot-password/{passwordResetCode}', array('as' => 'forgot-password-confirm', 'uses' => 'AuthController@getForgotPasswordConfirm'));
	Route::post('forgot-password/{passwordResetCode}', 'AuthController@postForgotPasswordConfirm');

	# Logout
	Route::get('logout', array('as' => 'logout', 'uses' => 'AuthController@getLogout'));

});

/*
|--------------------------------------------------------------------------
| Account Routes
|--------------------------------------------------------------------------
|
|
|
*/

Route::group(array('prefix' => 'account'), function()
{

	# Account Dashboard
	Route::get('/', array('as' => 'account', 'uses' => 'Controllers\Account\DashboardController@getIndex'));

	# Profilecd /www/var
	Route::get('profile', array('as' => 'profile', 'uses' => 'Controllers\Account\ProfileController@getIndex'));
	Route::post('profile', 'Controllers\Account\ProfileController@postIndex');

	# Change Password
	Route::get('change-password', array('as' => 'change-password', 'uses' => 'Controllers\Account\ChangePasswordController@getIndex'));
	Route::post('change-password', 'Controllers\Account\ChangePasswordController@postIndex');

	# Change Email
	Route::get('change-email', array('as' => 'change-email', 'uses' => 'Controllers\Account\ChangeEmailController@getIndex'));
	Route::post('change-email', 'Controllers\Account\ChangeEmailController@postIndex');

});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/



Route::get('/', array('as' => 'home', 'uses' => 'DashboardController@getIndex'));

if(! Sentry::check())
{
    Route::get('/', array('as' => 'home', 'uses' => 'AuthController@getSignin'));
}

//CURL HELPER TEST
Route::get('curl', array('as' => 'curl', 'uses' => 'HomeController@curlTest'));

App::missing(function($exception)
{
    //  App::abort(404, 'Page not found');
    //return Response::view('errors.missing', array(), 404);
    return View::make('notfound');
});

/*=======================================================================================================
=========================================================================================================
========================================================================================================*/

/*
 *
$namespace = 'Controllers\admin';

$routeTemplate = [
    'mainvoice' => $namespace . '\MainvoiceController',
    'mapayment'  => $namespace . '\MapaymentController',
    'merchant' => $namespace . '\MerchantController',
    'merchantagreement'   => $namespace . '\MerchantagreementController',

];


 // Standard Routes


foreach ($routeTemplate as $model => $controller) {
    Route::group(['prefix' => $model], function () use ($model, $controller) {

        # PATTERN
        Route::pattern('id', '[0-9]+');

        # GET /[model]/
        Route::get('/', [
            'as'     => $model . '.index',
            'before' => 'hasReadAccess:' . $model,
            'uses'   => $controller . '@index'
        ]);

        # GET /[model]/create
        Route::get('create', [
            'as'     => $model . '.create',
            'before' => 'hasWriteAccess:' . $model,
            'uses'   => $controller . '@create'
        ]);

        # POST /[model]/
        Route::post('/', [
            'as'     => $model . '.store',
            'before' => 'csrf|hasWriteAccess:' . $model,
            'uses'   => $controller . '@store'
        ]);

        # GET /[model]/{id}
        Route::get('{id}', [
            'as'     => $model . '.show',
            'before' => 'hasReadAccess:' . $model,
            'uses'   => $controller . '@show'
        ]);

        # GET /[model]/{id}/edit
        Route::get('{id}/edit', [
            'as'     => $model . '.edit',
            'before' => 'hasWriteAccess:' . $model,
            'uses'   => $controller . '@edit'
        ]);

        # PUT /[model]/{id}
        Route::put('{id}', [
            'as'     => $model . '.update',
            'before' => 'csrf|hasWriteAccess:' . $model,
            'uses'   => $controller . '@update'
        ]);

        # PATCH /[model]/{id}
        Route::patch('{id}', [
            'as'     => $model . '.patch',
            'before' => 'csrf|hasWriteAccess:' . $model,
            'uses'   => $controller . '@update'
        ]);

        # GET /[model]/{id}/delete
        Route::get('{id}/delete', [
            'as'     => $model . '.destroy',
            'before' => 'hasWriteAccess:' . $model,
            'uses'   => $controller . '@destroy'
        ]);

        # GET /[model]/{id}/restore
        Route::get('{id}/restore', [
            'as'     => $model . '.restore',
            'before' => 'hasWriteAccess:' . $model,
            'uses'   => $controller . '@restore'
        ]);
    });
}


 // No standard Routes


Route::get('login', [
    'as'     => 'session.create',
    'before' => null,
    'uses'   => $namespace . '\SessionController@create'
]);
Route::post('login', [
    'as'     => 'session.store',
    'before' => 'csrf',
    'uses'   => $namespace . '\SessionController@store'
]);
Route::get('logout', [
    'as'     => 'session.destroy',
    'before' => null,
    'uses'   => $namespace . '\SessionController@destroy'
]);


 // Landing Route


Route::get('/', [
    'as'     => 'landing.index',
    'before' => null,
    'uses'   => $namespace . '\LandingController@index'
]);

 */

