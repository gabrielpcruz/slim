<?php

use Adbar\Dot;
use App\Factory\AuthorizationServerFactory;
use App\Provider\AssetsTwigExtension;
use App\Repository\RepositoryManager;
use App\Repository\User\AccessTokenRepository;
use Illuminate\Database\Capsule\Manager;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use League\OAuth2\Server\CryptKey;
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
            $enviromentSettings = require __DIR__ . '/enviroment/development.php';
        }

        if (Application::isHomologation()) {
            $enviromentSettings = require __DIR__ . '/enviroment/homologation.php';
        }

        if (Application::isProduction()) {
            $enviromentSettings = require __DIR__ . '/enviroment/production.php';
        }

        $settings = array_replace_recursive($settings, $enviromentSettings);

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
        $viewSettings = $settings->get('view.settings');


        $loader = new FilesystemLoader([], $rootPath);

        foreach ($templates as $namespace => $template) {
            $loader->addPath($template, $namespace);
        }

        $twig = new Twig($loader, $viewSettings);

        $twig->addExtension(new DebugExtension());
        $twig->addExtension(new AssetsTwigExtension());

        return $twig;
    },

    RepositoryManager::class => autowire(),

    Illuminate\Database\ConnectionInterface::class => function (ContainerInterface $container) {
        return Manager::connection('default');
    },

    // OAuth
    AuthorizationServer::class => factory([
        AuthorizationServerFactory::class,
        'create',
    ]),

    BearerTokenValidator::class => function (ContainerInterface $container) {
        $oauth2PublicKey = $container->get('settings')->get('file.oauth_public');

        /** @var RepositoryManager $repositoryManager */
        $repositoryManager = $container->get(RepositoryManager::class);

        /** @var AccessTokenRepository $accessTokenRepository */
        $accessTokenRepository = $repositoryManager->get(AccessTokenRepository::class);

        $beareValidator = new BearerTokenValidator($accessTokenRepository);
        $beareValidator->setPublicKey(new CryptKey($oauth2PublicKey));

        return $beareValidator;
    }
];
