<?php

use App\Http\Site\Home;
use Slim\App;

return function (App $app) {
    $app->get('/', [Home::class, 'index']);
    $app->post('/arroz', [Home::class, 'arroz']);
};
