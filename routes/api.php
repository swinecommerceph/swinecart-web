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
header("Access-Control-Allow-Headers: Accept-Encoding, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE");


Route::group(['middleware' => 'api', 'namespace' => 'Api'], function() {
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function() {
        Route::post('/register', 'RegisterController@register');
        Route::post('/login', 'LoginController@normalLogin');
        Route::get('/me', 'LoginController@me');
        Route::post('/logout', 'LogoutController@logout');
    });

    Route::group(['prefix' => 'notifications'], function() {
        Route::get('/', 'NotificationController@getNotifications');
        Route::patch('/{id}', 'NotificationController@SeeNotification');
    });

    Route::group(['prefix' => 'chats'], function() {
        Route::get('/', 'ChatController@getChats');
        Route::get('/{id}', 'ChatController@getConversation');
    });

    Route::group(['prefix' => 'farms'], function() {
        Route::get('/', 'FarmController@getFarms');
        Route::get('/{id}', 'FarmController@getFarm');
        Route::post('/', 'FarmController@addFarm');
        Route::put('/{id}', 'FarmController@updateFarm');
    });

    Route::group(['prefix' => 'provinces'], function() {
        Route::get('/', 'ProvinceController@getProvinces');
    });

    Route::group(['prefix' => 'profile'], function() {
        Route::get('/', 'ProfileController@getProfile');
    });

    Route::group(['namespace' => 'Breeder', 'prefix' => 'breeder'], function() {

        Route::group(['prefix' => 'products'], function() {
            Route::get('/', 'ProductController@getProducts');
            Route::delete('/', 'ProductController@deleteProducts');
            Route::patch('/', 'ProductController@updateSelected');

            Route::get('/{id}', 'ProductController@getProduct');
            Route::get('/{id}/details', 'ProductController@getProductDetails');

            Route::post('/', 'ProductController@addProduct');
            Route::put('/{id}', 'ProductController@updateProduct');
            Route::patch('/{id}/status', 'ProductController@toggleProductVisibility');

            Route::get('/{id}/media', 'ProductController@getMedia');
            Route::post('/{id}/media', 'ProductController@addMedia');
            Route::patch('/{id}/media', 'ProductController@setPrimaryPicture');
            Route::delete('/{id}/media', 'ProductController@deleteMedia');

        });

        Route::group(['prefix' => 'dashboard'], function() {
            Route::get('/stats', 'DashboardController@getDashBoardStats');
            Route::get('/ratings', 'DashboardController@getRatings');
            Route::get('/reviews', 'DashboardController@getReviews');
        });

        Route::group(['prefix' => 'orders'], function() {
            Route::get('/{status}', 'OrderController@getOrders');
            Route::get('/{id}/requests', 'OrderController@getRequests');
            Route::delete('/{id}/requests', 'OrderController@deleteRequest');
            Route::post('/{id}/order-status', 'OrderController@updateOrderStatus');
            Route::delete('/{id}/order-status', 'OrderController@cancelTransaction');
        });
    });

    Route::group(['namespace' => 'Customer', 'prefix' => 'customer'], function() {

        Route::group(['prefix' => 'shop'], function() {
            Route::get('/products', 'ShopController@getProducts');
            Route::get('/filters', 'ShopController@getFilterOptions');
        });

        Route::group(['prefix' => 'cart'], function() {
            Route::get('/items', 'CartController@getItems');
            Route::get('/items/{id}', 'CartController@getItem');
            Route::post('/items/{id}', 'CartController@addItem');
            Route::delete('/items/{id}', 'CartController@deleteItem');
        });

        Route::group(['prefix' => 'orders'], function() {
            Route::get('/', 'OrderController@getOrders');
            Route::get('/history', 'OrderController@getHistory');
            Route::get('/{id}', 'OrderController@getOrder');

            Route::post('/reviews/{id}', 'OrderController@reviewBreeder');
            Route::post('/{id}', 'OrderController@requestItem');
        });
    });

});
