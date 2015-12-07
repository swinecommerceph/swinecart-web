<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/',['as' => 'index_path', function () {
	// Auth::loginUsingId(2);
    return view('home');
}])->middleware('guest');

// Authentication Routes
Route::get('login',['as' => 'getLogin_path', 'uses' => 'Auth\AuthController@getLogin']);
Route::post('login',['as' => 'postLogin_path', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('logout',['as' => 'logout_path', 'uses' => 'Auth\AuthController@getLogout']);

// Registration Routes
Route::get('register',['as' => 'getRegister_path', 'uses' => 'Auth\AuthController@getRegister']);
Route::post('register',['as' => 'postRegister_path', 'uses' => 'Auth\AuthController@postRegister']);

/**
* User Routes according to roles
*/
// General controller of the routing according to roles
Route::get('/home',['as' => 'home_path', 'uses' => 'UserController@index']);

// Breeder
Route::get('/home/breeder',['as' => 'breeder_path', 'uses' => 'BreederController@index']);

// Customer
Route::get('/home/customer',['as' => 'customer_path', 'uses' => 'CustomerController@index']);
