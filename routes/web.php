<?php


use App\Http\Site\Auth\Login;
use App\Http\Site\Documentation\Documentation;
use App\Http\Site\Home\Home;
use App\Middleware\Site\Authentication\AuthenticationSite;
use Slim\App;

return function (App $app) {
    $app->redirect('/', '/login');

    $app->get('/maintenance', [Home::class, 'maintenance']);
    $app->get('/route_maintenance', [Home::class, 'route_maintenance']);


    $app->get('/home', [Home::class, 'index']);

    $app->get('/logged', [Home::class, 'logged'])->add(AuthenticationSite::class);

    $app->get('/login', [Login::class, 'index']);
    $app->post('/login', [Login::class, 'login']);
    $app->get('/logout', [Login::class, 'logout']);

    $app->get('/docs', [Documentation::class, 'index']);
};
