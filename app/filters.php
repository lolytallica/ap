<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	// Check if the user is logged in
	if ( ! Sentry::check())
	{
		// Store the current uri in the session
		Session::put('loginRedirect', Request::url());

		// Redirect to the login page
		return Redirect::route('signin');
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| Admin authentication filter.
|--------------------------------------------------------------------------
|
| This filter does the same as the 'auth' filter but it checks if the user
| has 'admin' privileges.
|
*/

Route::filter('admin-auth', function()
{
	// Check if the user is logged in
	if ( ! Sentry::check())
	{
		// Store the current uri in the session
		Session::put('loginRedirect', Request::url());

		// Redirect to the login page
		return Redirect::route('signin');
	}

/*@todo: custom group access*/

	// Check if the user has access to the admin page
	/* if ( ! Sentry::getUser()->hasAccess('admin') && ! Sentry::getUser()->hasAccess('manage_partner_invoices') && !Sentry::getUser()->hasAccess('manage_ma_invoices') && !Sentry::getUser()->hasAccess('manage_groups') && !Sentry::getUser()->hasAccess('manage_ma_payments'))
	{
		// Show the insufficient permissions page
		return App::abort(403);
	}*/
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

/*
if(Sentry::check())
{
Route::filter('hasAccess', function($route, $request, $value)
{
    $request = Request::url();
    $user = Sentry::getUser();
    if (!$user->hasAccess($value))
    {
        Session::flash('error', 'Access denied!');
        return Redirect::to('/');
    }
});
}*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});



Route::filter('hasAccess', function($route, $request, $value)
{
    try
    {
        $user = Sentry::getUser();

        if( !Sentry::check() ||  ! $user->hasAccess($value))
        {
            return Redirect::route('admin')->withErrors('Access Denied');
        }
    }
    catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
    {
        return Redirect::route('admin')->withErrors('Access Denied');
    }

});


/*=============================================
===============================================*/


/*
 *
 Route::filter('hasReadAccess', function ($route, $request, $value) {
    // I used Sentry, but change it to whatever you need.
    if (! Sentry::getUser()->hasAccess($value . '.read')) App::abort(401, 'You are not authorized.');
});

Route::filter('hasWriteAccess', function ($route, $request, $value) {
    // I used Sentry, but change it to whatever you need.
    if (! Sentry::getUser()->hasAccess($value . '.write')) App::abort(401, 'You are not authorized.');
});

 * */