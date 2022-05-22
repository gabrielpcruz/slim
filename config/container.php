<?php

use Adbar\Dot;
use Slim\App;
use Slim\Factory\AppFactory;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [

    'settings' => function () {
        return new Dot(require __DIR__ . '/settings.php');
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

        $template_path = $settings->get('template.templates_path');
        $cache = $settings->get('template.cache_path');

        $loader = new FilesystemLoader($template_path);

        return new Environment($loader, [
            'cache' => $cache
        ]);
    },
];
