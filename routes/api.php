<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    $router->post('/login', 'AuthController@login');
    $router->post('/logout', 'AuthController@logout');
    $router->post('/refresh', 'AuthController@refresh');
    $router->post('/user', 'AuthController@user');

});

Route::group([
    'middleware' => 'api',
    'prefix' => 'po'
], function ($router) {
    $router->get('/list/{type}', 'PurchaseOrderController@listPO');
    $router->post('/create', 'PurchaseOrderController@createPO');
    $router->get('/{type}/{id}', 'PurchaseOrderController@approval');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'customer'
], function ($router) {
    $router->post('/store', 'CustomerController@store');
    $router->get('/get-data-list', 'CustomerController@listCustomer');
});
