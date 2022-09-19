<?php

use App\Http\Api\Auth\Token;
use App\Http\Site\Documentation;
use App\Http\Site\Home;
use App\Middleware\Authentication;
use App\Middleware\ProfileAccess\Administrator;
use Slim\App;

use App\Http\Api\Home as HomeApi;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Docs
    $app->redirect('/', '/docs');

    $app->get('/home', [Home::class, 'index']);

    $app->get('/docs', [Documentation::class, 'index']);

    $app->group('/api', function (RouteCollectorProxy $api) {

        $api->group('/v1', function (RouteCollectorProxy $proxy) {
            // Any user
            $proxy->get('/home', [HomeApi::class, 'index']);

            // Admin
            $proxy->get('/home/2', [HomeApi::class, 'index'])->add(Administrator::class);

        })->add(Authentication::class);

        $api->get('/v1/rice', [HomeApi::class, 'index']);
    });

    $app->post('/token', [Token::class, 'index']);
};
