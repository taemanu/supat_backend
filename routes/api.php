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
    $router->post('/change-status', 'CustomerController@ChangeStatus');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'qt'
], function ($router) {
    $router->get('/list', 'QuotationController@index');
    $router->get('/create', 'QuotationController@create');
    $router->post('/store', 'QuotationController@store');
    $router->get('/edit/{id}', 'QuotationController@edit');
    $router->post('/update/{id}', 'QuotationController@update');
    $router->delete('/destroy/{id}', 'QuotationController@destroy');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'project'
], function ($router) {
    $router->post('/store', 'ProjectController@store');
    $router->get('/get-data-list', 'ProjectController@listProject');
    $router->post('/change-status', 'ProjectController@ChangeStatus');
    $router->get('/project-detail/{code}', 'ProjectController@projectDetail');
    $router->get('/task_detail/{code}', 'ProjectController@tasktDetail');
});
