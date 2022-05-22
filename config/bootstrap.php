<?php

use DI\ContainerBuilder;
use DI\Container;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\App;

// Settings
require __DIR__ . '/../vendor/autoload.php';

$settings = [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },
    App::class => function (ContainerInterface $container) {
        return AppFactory::createFromContainer($container);
    }
];

$containerBuild = new ContainerBuilder(Container::class);
$containerBuild->addDefinitions($settings);
$container = $containerBuild->build();

$app = $container->get(App::class);

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello worlds!");
    return $response;
});

return $app;
