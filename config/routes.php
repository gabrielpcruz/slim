<?php

use Slim\App;

use App\Http\Site\Home;
use App\Http\Api\Home as HomeApi;

return function (App $app) {
    // Site
    $app->get('/', [Home::class, 'index']);

    // Api
    $app->get('/v1/home', [HomeApi::class, 'index']);
};
