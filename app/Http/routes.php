<?php

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

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['prefix' => 'api/v1', 'namespace' => 'App\Http\Controllers'], function ($app) {
    $app->get('location', 'LocationController@index');
    $app->get('location/{id}', 'LocationController@get');
    $app->post('location', 'LocationController@create');
    $app->put('location/{id}', 'LocationController@update');
    $app->delete('location/{id}', 'LocationController@delete');
});
