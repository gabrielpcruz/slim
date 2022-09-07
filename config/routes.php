<?php

use App\Http\Api\Auth\Token;
use App\Http\Site\Documentation;
use App\Http\Site\Home;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Middleware\AuthorizationServerMiddleware;
use Slim\App;
use App\App as A;

use App\Http\Api\Home as HomeApi;

return function (App $app) {
    // Docs
    $app->redirect('/', '/docs');
    $app->get('/docs', [Documentation::class, 'index']);

    // Api
    $app->get('/v1/home', [HomeApi::class, 'index']);

    $app->get('/home', [Home::class, 'index']);

    $app->post('/token', [Token::class, 'index']);

};
