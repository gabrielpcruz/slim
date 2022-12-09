<?php

use App\Http\Api\Auth\Token;
use App\Http\Api\Home;
use App\Middleware\Authentication\Api\AuthenticationApi;
use App\Middleware\ProfileAccess\Administrator;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('/api', function (RouteCollectorProxy $api) {

        $api->group('/v1', function (RouteCollectorProxy $proxy) {
            // Any user
            $proxy->get('/home', [Home::class, 'index']);

            // Admin
            $proxy->get('/home/2', [Home::class, 'index'])->add(Administrator::class);

        })->add(AuthenticationApi::class);

        $api->get('/v1/rice', [Home::class, 'index']);

        $api->post('/token', [Token::class, 'create']);
    });
};