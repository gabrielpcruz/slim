<?php

use App\Factory\ContainerFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

// Settings
require __DIR__ . '/../vendor/autoload.php';

$container = (new ContainerFactory())->createInstance();

$app = $container->get(App::class);

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello worlds!");
    return $response;
});

return $app;
