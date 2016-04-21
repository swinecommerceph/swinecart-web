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

//Route Model Binding
// $router->bind('customer', function($customer)
// {
//     return App\Models\Customer::whereSlug($slug)->first();
// });

Route::get('/',['as' => 'index_path', function () {
    return view('home');
}])->middleware('guest');

Route::group(['middleware' => ['web']], function () {

    /**
     * Authentication and Registration Routes
     */
    // Normal Authentication Routes
    Route::get('login',['as' => 'getLogin_path', 'uses' => 'Auth\AuthController@getLogin']);
    Route::post('login',['as' => 'postLogin_path', 'uses' => 'Auth\AuthController@postLogin']);
    Route::get('logout',['as' => 'logout_path', 'uses' => 'Auth\AuthController@getLogout']);

    // Third-party Authentication Routes
    Route::get('login/{provider}',['as' => 'provider.redirect', 'uses' => 'Auth\AuthController@redirectToProvider']);
    Route::get('login/{provider}/callback',['as' => 'provider.handle', 'uses' => 'Auth\AuthController@handleProviderCallback']);

    // Registration Routes
    Route::get('register',['as' => 'getRegister_path', 'uses' => 'Auth\AuthController@getRegister']);
    Route::post('register',['as' => 'postRegister_path', 'uses' => 'Auth\AuthController@postRegister']);

    // Email Verification for Authentication
    Route::get('login/redirect/email/{email}/ver-code/{verCode}', ['as' => 'verCode.send', 'uses' => 'Auth\AuthController@verifyCode']);
    Route::get('login/resend/email/{email}/ver-code/{verCode}', ['as' => 'verCode.resend', 'uses' => 'Auth\AuthController@resendCode']);

    /**
    * User Routes according to roles
    */
    // General controller of the routing according to roles
    Route::get('/home',['as' => 'home_path', 'uses' => 'UserController@index']);

    // Breeder
    Route::group(['prefix' => 'breeder'], function(){

    	Route::get('home',['as' => 'breeder_path', 'uses' => 'BreederController@index']);

        // profile-related
    	Route::get('edit-profile',['as' => 'breeder.edit', 'uses' => 'BreederController@editProfile']);
    	Route::post('edit-profile',['as' => 'breeder.store', 'uses' => 'BreederController@storeProfile']);
        Route::put('edit-profile/personal/edit',['as' => 'breeder.updatePersonal', 'uses' => 'BreederController@updatePersonal']);
        Route::post('edit-profile/farm/add',['as' => 'breeder.addFarm', 'uses' => 'BreederController@addFarm']);
        Route::put('edit-profile/farm/edit',['as' => 'breeder.updateFarm', 'uses' => 'BreederController@updateFarm']);
        Route::delete('edit-profile/farm/delete',['as' => 'breeder.deleteFarm', 'uses' => 'BreederController@deleteFarm']);

        // product-related
        Route::get('products',['as' => 'products', 'uses' => 'BreederController@showProducts']);
        Route::post('products',['as' => 'products.store', 'uses' => 'BreederController@storeProducts']);
        Route::post('products/media/upload',['as' => 'products.mediaUpload', 'uses' => 'BreederController@uploadMedia']);
        Route::delete('products/media/delete',['as' => 'products.mediaDelete', 'uses' => 'BreederController@deleteMedium']);

    });


    // Customer
    Route::group(['prefix' => 'customer'], function(){

    	Route::get('home',['as' => 'customer_path', 'uses' => 'CustomerController@index']);

        // profile-related
    	Route::get('edit-profile',['as' => 'customer.edit', 'uses' => 'CustomerController@editProfile']);
    	Route::post('edit-profile',['as' => 'customer.store', 'uses' => 'CustomerController@storeProfile']);
    	Route::put('edit-profile/personal/edit',['as' => 'customer.updatePersonal', 'uses' => 'CustomerController@updatePersonal']);
        Route::post('edit-profile/farm/add',['as' => 'customer.addFarm', 'uses' => 'CustomerController@addFarm']);
        Route::put('edit-profile/farm/edit',['as' => 'customer.updateFarm', 'uses' => 'CustomerController@updateFarm']);
        Route::delete('edit-profile/farm/delete',['as' => 'customer.deleteFarm', 'uses' => 'CustomerController@deleteFarm']);

        // product-related
        Route::get('view-products',['as' => 'products.view', 'uses' => 'CustomerController@viewProducts']);
        Route::get('view-products/{product}',['as' => 'products.viewDetail', 'uses' => 'CustomerController@viewProductDetail']);

        // swinecart-related
        Route::get('swine-cart',['as' => 'cart.items', 'uses' => 'CustomerController@getSwineCartItems']);
        Route::post('swine-cart/add',['as' => 'cart.add', 'uses' => 'CustomerController@addToSwineCart']);
        Route::delete('swine-cart/delete',['as' => 'cart.delete', 'uses' => 'CustomerController@deleteFromSwineCart']);
        Route::get('swine-cart/quantity',['as' => 'cart.quantity', 'uses' => 'CustomerController@getSwineCartQuantity']);

    });

});
