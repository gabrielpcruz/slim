<?php

use App\Http\Site\Documentation;
use Slim\App;

use App\Http\Api\Home as HomeApi;

return function (App $app) {
    // Docs
    $app->redirect('/', '/docs');
    $app->get('/docs', [Documentation::class, 'index']);

    // Api
    $app->get('/v1/home', [HomeApi::class, 'index']);
};
