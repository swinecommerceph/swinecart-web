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
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Accept-Encoding, Content-Encoding, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE");

Route::group(['middleware' => 'api', 'namespace' => 'Api'], function() {
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function() {
        Route::post('/register', 'RegisterController@register');
        Route::post('/login', 'LoginController@normalLogin');
        Route::get('/me', 'LoginController@me');
        Route::post('/logout', 'LogoutController@logout');
    });

    Route::group(['namespace' => 'Breeder', 'prefix' => 'breeder'], function() {
        Route::group(['prefix' => 'profile'], function() {
            Route::get('/', 'EditProfileController@getProfile');
            Route::put('/', 'EditProfileController@updatePersonal');

            Route::patch('/password', 'EditProfileController@changePassword');
            
            Route::get('/farms', 'EditProfileController@getFarms');

            Route::get('/farms/{id}', 'EditProfileController@getFarm');
            Route::put('/farms/{id}', 'EditProfileController@updateFarm');
            Route::delete('/farms/{id}', 'EditProfileController@deleteFarm');
            
            // Route::post('/upload-logo', 'EditProfileController@uploadLogo');
            // Route::delete('/delete-logo', 'EditProfileController@deleteLogo');
            // Route::post('/set-logo', 'EditProfileController@setLogo');
        });

        Route::group(['prefix' => 'products'], function() {
            Route::get('/', 'ProductController@getProducts');
            Route::get('/filter', 'ProductController@filterProducts');
            Route::delete('/', 'ProductController@deleteProducts');
            Route::patch('/', 'ProductController@updateSelected');

            Route::get('/{id}', 'ProductController@getProduct');
            Route::get('/{id}/details', 'ProductController@getProductDetails');
            Route::post('/', 'ProductController@addProduct');
            Route::put('/{id}', 'ProductController@updateProduct');
            Route::patch('/{id}/status', 'ProductController@toggleProductStatus');


            // Route::post('/set-primary-picture', 'ProductController@setPrimaryPicture');
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
            Route::get('/', 'NotificationsController@getNotifications');
            Route::get('/count', 'NotificationsController@getNotificationsCount');
            Route::post('/see/{id}', 'NotificationsController@SeeNotification');
        });

        Route::group(['prefix' => 'messages'], function() {
            Route::get('/threads', 'MessageController@getThreads');
            Route::get('/unread/count', 'MessageController@unreadCount');
            Route::get('/{id}', 'MessageController@getMessages');
        });
    });
 
    Route::group(['namespace' => 'Customer', 'prefix' => 'customer'], function() {
        Route::group(['prefix' => 'profile'], function() {
            Route::get('/', 'EditProfileController@me');
            Route::put('/', 'EditProfileController@updatePersonal');
            Route::patch('/password', 'EditProfileController@changePassword');

            Route::get('/farms', 'EditProfileController@getFarms');
            Route::get('/farms/{id}', 'EditProfileController@getFarm');

            Route::post('/farms', 'EditProfileController@addFarm');
            Route::put('/farms/{id}', 'EditProfileController@updateFarm');
            Route::delete('/farms/{id}', 'EditProfileController@deleteFarm');
            
            Route::get('/provinces', 'EditProfileController@getProvinces');
            Route::get('/breeders', 'EditProfileController@getBreeders');
        });

        Route::group(['prefix' => 'products'], function() {
            Route::post('/', 'ProductController@getProducts');
            Route::post('/filter', 'ProductController@filterProducts');
            Route::get('/product/detail/{id}', 'ProductController@getProductDetail');
            Route::get('/breeder/{id}', 'ProductController@getBreederProfile');
            Route::get('/breeds', 'ProductController@getBreeds');
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
            Route::get('/threads', 'MessageController@getThreads');
            Route::get('/unread/count', 'MessageController@unreadCount');
            Route::get('/{id}', 'MessageController@getMessages');
        });
    });
    
});
