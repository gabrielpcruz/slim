<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

use App\Http\Site\Home;
use App\Http\Api\Home as HomeApi;

return function (App $app) {
    // Site
    $app->group('/', function (RouteCollectorProxy $group) {
        $group->get('', [Home::class, 'index']);

        $group->post('', [Home::class, 'arroz']);
    });
//
//    $app->get('/arroz', [Home::class, 'terra']);

    // Api

    $app->get('/api/v1/home', [HomeApi::class, 'index']);


};
