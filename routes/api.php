<?php

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Router;

/** @var Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| Simply tell Lumen the URIs it should respond to
| and give it the Closure or controller method to call when requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'internal', 'middleware' => 'version'], function () use ($router) {
    $router->post('/redeemcode/generate', 'RedeemCodeController@generateRedeemCode');
    $router->post('/redeemcode/list', 'RedeemCodeController@getRedeemCodeList');

    $router->post('/test/url', 'API\TestController@testUrl');
    $router->post('/test/host', 'API\TestController@testHost');

    $router->post('/broadcast/notification', 'NotificationController@sendSmsOnlyPush');
    $router->post('/rebroadcast/notification', 'NotificationController@resendSmsOnlyPush');
});
