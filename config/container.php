<?php

use Adbar\Dot;
use SlashTrace\SlashTrace;
use Slim\App;
use Slim\Factory\AppFactory;
use Psr\Container\ContainerInterface;
use Slim\Views\Twig;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use function DI\factory;

return [

    'settings' => function () {
        $settings = require __DIR__ . '/settings.php';

        return new Dot($settings);
    },

    App::class => function (ContainerInterface $container) {
        $app = AppFactory::createFromContainer($container);

        // Adding routes of application
        (require __DIR__ . '/routes.php')($app);

        $app->addRoutingMiddleware();

        return $app;
    },

    Twig::class => function (ContainerInterface $container) {
        $settings = $container->get('settings');

        $rootPath = $settings->get('view.path');
        $templates = $settings->get('view.templates');
        $settings = $settings->get('view.settings');


        $loader = new FilesystemLoader([], $rootPath);

        foreach ($templates as $namespace => $template) {
            $loader->addPath($template, $namespace);
        }

        $twig = new Twig($loader, $settings);

        $twig->addExtension(new DebugExtension());

        return $twig;
    },
];
