<?php

use App\Http\Api\Auth\Token;
use App\Http\Site\Documentation;
use App\Http\Site\Home;
use App\Middleware\Authentication;

use Slim\App;

use App\Http\Api\Home as HomeApi;

return function (App $app) {
    // Docs
    $app->redirect('/', '/docs');
    $app->get('/docs', [Documentation::class, 'index']);

    // Api
    $app->get('/v1/home', [HomeApi::class, 'index'])->add(Authentication::class);

    $app->get('/home', [Home::class, 'index']);

    $app->post('/token', [Token::class, 'index']);

};
