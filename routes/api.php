<?php

use Illuminate\Http\Request;

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

/* Setup CORS */
// header('Access-Control-Allow-Origin: *');
// header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
// header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

Route::group(['middleware' => 'api', 'namespace' => 'Api'], function() {
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function() {
        Route::post('/register', 'RegisterController@register');
        Route::post('/login', 'LoginController@normalLogin');
        Route::post('/me', 'LoginController@me');
        Route::post('/logout', 'LogoutController@logout');
    });

    Route::group(['namespace' => 'User', 'prefix' => 'user'], function() {
    });
});
