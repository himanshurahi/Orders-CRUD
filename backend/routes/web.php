<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//add api routes
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('orders',  ['uses' => 'OrdersController@getList']);
    $router->post('orders', ['uses' => 'OrdersController@createItem']);
    $router->post('orders/action', ['uses' => 'OrdersController@itemAction']);
    $router->get('orders/{id}', ['uses' => 'OrdersController@getItem']);
    $router->put('orders/{id}', ['uses' => 'OrdersController@updateItem']);
});
