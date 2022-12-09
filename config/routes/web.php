<?php

use App\Http\Site\Documentation;
use App\Http\Site\Home;
use App\Http\Site\Login;
use App\Middleware\Authentication\Site\AuthenticationSite;
use Slim\App;

return function (App $app) {
    $app->redirect('/', '/login');

    $app->get('/home', [Home::class, 'index']);

    $app->get('/logado', [Home::class, 'logado'])->add(AuthenticationSite::class);

    $app->get('/login', [Login::class, 'index']);
    $app->post('/login', [Login::class, 'login']);
    $app->get('/logout', [Login::class, 'logout']);

    $app->get('/docs', [Documentation::class, 'index']);
};
