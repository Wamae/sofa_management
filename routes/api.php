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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/login', 'AccountController@login');

Route::resource('/accounts', 'AccountController');

Route::resource('/customers', 'CustomerController');
Route::get('/get_all_customers', 'CustomerController@getAllCustomers');

Route::resource('/chair_types', 'ChairTypeController');
Route::get('/get_all_chair_types', 'ChairTypeController@getAllChairTypes');

Route::resource('/chairs', 'ChairController');
Route::get('/get_all_chairs', 'ChairController@getAllChairs');

Route::resource('/order_statuses', 'OrderStatusController');
Route::get('/get_all_order_statuses', 'OrderStatusController@getAllOrderStatuses');

Route::resource('/orders', 'OrderController');
Route::get('/get_all_orders', 'OrderController@getAllOrders');


/*Route::post('user/register', 'APIRegisterController@register');
Route::post('user/login', 'APILoginController@login');

Route::middleware('jwt.auth')->get('users', function(Request $request) {
    return auth()->user();
});*/
