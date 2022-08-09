<?php

use Adbar\Dot;
use App\Repository\RepositoryManager;
use Illuminate\Database\Capsule\Manager;
use Slim\App;
use Slim\Factory\AppFactory;
use Psr\Container\ContainerInterface;
use Slim\Views\Twig;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use function DI\autowire;
use function DI\factory;
use App\App as Application;

return [

    'settings' => function () {
        $settings = (require_once __DIR__ . '/settings.php');
        $enviromentSettings = [];

        if (Application::isDevelopment()) {
//            $enviromentSettings = require __DIR__ . '/enviroment/development.php';
        }

        if (Application::isHomologation()) {
//            $enviromentSettings = require __DIR__ . '/enviroment/homologation.php';
        }

        if (Application::isProduction()) {
//            $enviromentSettings = require __DIR__ . '/enviroment/production.php';
        }

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

    RepositoryManager::class => autowire(),

    Illuminate\Database\ConnectionInterface::class => function (ContainerInterface $container) {
        return Manager::connection('default');
    },
];
