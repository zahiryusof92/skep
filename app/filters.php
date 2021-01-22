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

App::before(function($request) {
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {

        header('Access-Control-Allow-Origin', 'https://patrick.odesi.tech/');
        header('Allow', 'GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials', 'true');

        exit;
    }
});


App::after(function($request, $response) {

    $response->headers->set('Access-Control-Allow-Origin', 'https://patrick.odesi.tech/');
    $response->headers->set('Access-Control-Allow-Headers', 'GET, POST, OPTIONS, PUT, DELETE, X-Requested-With, Content-Type, Authorization');
    $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE');

    return $response;
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

Route::filter('authUser', function() {
    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('/');
//            return Redirect::guest('/');
        }
    }
});

Route::filter('authMember', function() {

    if (Auth::guest()) {
        if (Request::ajax()) {

            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('/');

//            return Redirect::guest('/');
        }
    }
});

Route::filter('auth.basic', function() {
    if ((app('request')->header('php-auth-user') != null && app('request')->header('php-auth-pw') != null
            )) {
        $input = array('username' => app('request')->header('php-auth-user'),
            'password' => app('request')->header('php-auth-pw'));
        Input::merge($input);
        return Auth::basic('username');
    } else {
        return Auth::basic();
    }
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

Route::filter('guest', function() {
    if (Auth::check()) {
        return Redirect::to('/home');
    }
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

Route::filter('csrf', function() {
    if (Session::token() !== Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});
