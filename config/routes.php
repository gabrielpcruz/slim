<?php

use App\Http\Site\Home;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('/', function (RouteCollectorProxy $group) {
        $group->get('', [Home::class, 'index']);

        $group->post('', [Home::class, 'arroz']);
    });

    $app->get('/arroz', [Home::class, 'terra']);

};
