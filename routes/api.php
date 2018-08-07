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

    Route::group(['namespace' => 'Breeder', 'prefix' => 'breeder'], function() {
        Route::group(['prefix' => 'edit-profile'], function() {
            Route::get('/get', 'EditProfileController@getProfile');

            Route::post('/change-password', 'EditProfileController@changePassword');
            Route::post('/update-personal', 'EditProfileController@updatePersonal');
            
            Route::post('/farm/update/{id}', 'EditProfileController@updateFarm');
            Route::delete('/farm/delete/{id}', 'EditProfileController@deleteFarm');

            // Route::post('/upload-logo', 'EditProfileController@uploadLogo');
            // Route::delete('/delete-logo', 'EditProfileController@deleteLogo');
            // Route::post('/set-logo', 'EditProfileController@setLogo');
        });

        Route::group(['prefix' => 'products'], function() {
            Route::get('/get', 'ProductController@getProducts');
            Route::post('/get', 'ProductController@filterProducts');
            Route::get('/get-farms', 'ProductController@getFarms');

            Route::post('/product/display/{id}', 'ProductController@displayProduct');
            Route::post('/product/update/{id}', 'ProductController@updateProduct');
            Route::post('/product/store', 'ProductController@storeProduct');
            Route::post('/product/update-selected', 'ProductController@updateSelected');
            Route::post('/product/delete-selected', 'ProductController@deleteSelected');
            Route::post('/set-primary-picture', 'ProductController@setPrimaryPicture');
            Route::get('/product/summary/{id}', 'ProductController@getProductSummary');
            Route::get('/product/detail/{id}', 'ProductController@getProductDetail');
            
            // Route::delete('/media/delete', 'ProductController@deleteMedium');

        });

        Route::group(['prefix' => 'dashboard'], function() {
            Route::get('/stats', 'DashboardController@getDashBoardStats');
            Route::get('/latest-accre', 'DashboardController@getLatestAccre');
            Route::get('/server-date', 'DashboardController@getServerDate');
            Route::get('/sold-data', 'DashboardController@getSoldData');

            Route::get('/product-status', 'DashboardController@getProductStatus');
            Route::get('/review-ratings', 'DashboardController@getReviewAndRatings');
            
            Route::get('/product-requests/{id}', 'DashboardController@getProductRequests');
            Route::post('/sold-products', 'DashboardController@getSoldProducts');

            Route::post('/product-status/{id}', 'DashboardController@updateProductStatus');

            Route::get('/customer-info/{id}', 'DashboardController@getCustomerInfo');

            Route::get('/customers', 'DashboardController@getCustomers');
        });

        Route::group(['prefix' => 'notifications'], function() {
            Route::get('/get', 'NotificationsController@getNotifications');
            Route::get('/count', 'NotificationsController@getNotificationsCount');
            Route::post('/see/{id}', 'NotificationsController@SeeNotification');
        });

        Route::group(['prefix' => 'messages'], function() {
            Route::get('/threads', 'MessageController@getThreads');
            Route::get('/{id}', 'MessageController@getMessages');
        });
    });
    
    
    
    Route::group(['namespace' => 'Customer', 'prefix' => 'customer'], function() {
        Route::group(['prefix' => 'edit-profile'], function() {
            Route::get('/me', 'EditProfileController@me');
            Route::get('/farm-addresses', 'EditProfileController@getFarmAddresses');
            Route::get('/provinces', 'EditProfileController@getProvinces');

            Route::post('/change-password', 'EditProfileController@changePassword');
            Route::post('/update-personal', 'EditProfileController@updatePersonal');

            Route::post('/farm/add', 'EditProfileController@addFarm');
            Route::post('/farm/update/{id}', 'EditProfileController@updateFarm');
            Route::delete('/farm/delete/{id}', 'EditProfileController@deleteFarm');

            Route::get('/breeders', 'EditProfileController@getBreeders');
        });

        Route::group(['prefix' => 'products'], function() {
            Route::get('/product/detail/{id}', 'ProductController@getProductDetail');
            Route::get('/breeder/{id}', 'ProductController@getBreederProfile');
        });

        Route::group(['prefix' => 'swine-cart'], function() {
            Route::get('/items', 'SwineCartController@getItems');
            Route::get('/items/count', 'SwineCartController@getItemCount');
            Route::post('/items/add/{id}', 'SwineCartController@addItem');
            Route::delete('/items/delete/{id}', 'SwineCartController@deleteItem');
            Route::post('/items/request/{id}', 'SwineCartController@requestItem');
            Route::get('/transactions/{id}', 'SwineCartController@getTransactionHistory');
            Route::post('/rate-breeder/{id}', 'SwineCartController@rateBreeder');
        });

        Route::group(['prefix' => 'notifications'], function() {
            Route::get('/get', 'NotificationsController@getNotifications');
            Route::get('/count', 'NotificationsController@getNotificationsCount');
            Route::post('/see/{id}', 'NotificationsController@SeeNotification');
        });

        Route::group(['prefix' => 'messages'], function() {

        });
    });
    
});
