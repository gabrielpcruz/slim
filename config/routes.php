<?php

use App\Http\Api\Auth\Token;
use App\Http\Site\Documentation;
use App\Http\Site\Home;
use App\Middleware\Authentication;
use App\Middleware\ProfileAccess\Admin;
use Slim\App;

use App\Http\Api\Home as HomeApi;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Docs
    $app->redirect('/', '/docs');
    $app->get('/docs', [Documentation::class, 'index']);

    $app->group('/v1', function (RouteCollectorProxy $v1) {
        // Api
        $v1->get('/home', [HomeApi::class, 'index']);

        // Api
        $v1->get('/home/2', [HomeApi::class, 'index'])->add(Admin::class);

    })->add(Authentication::class);

    $app->get('/home', [Home::class, 'index']);

    $app->post('/token', [Token::class, 'index']);
};
