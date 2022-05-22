<?php

use Slim\App;
use Slim\Factory\AppFactory;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [

    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    App::class => function (ContainerInterface $container) {
        $app = AppFactory::createFromContainer($container);

        // Adding routes of application
        (require __DIR__ . '/routes.php')($app);

        $app->addRoutingMiddleware();

        return $app;
    },

    'twig' => function (ContainerInterface $container) {
        $settings = $container->get('settings');

        var_dump($settings);
        $cache = '';

        $loader = new FilesystemLoader('$tempates');

        return new Environment($loader, [
            'cache' => $cache
        ]);
    }
];
