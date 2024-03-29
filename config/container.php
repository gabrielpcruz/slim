<?php

use Adbar\Dot;
use App\App as Application;
use App\Repository\User\AccessTokenRepository;
use App\Service\Token\AuthorizationServer as SlimAuthorizationServer;
use App\Slim\Mail\Mailer;
use App\Slim\Mail\MailerPHPMailer;
use Illuminate\Database\Capsule\Manager;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use League\OAuth2\Server\CryptKey;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use App\Slim\Repository\RepositoryManager;
use Twig\Extension\DebugExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;
use function DI\autowire;
use function DI\factory;

return [

    'settings' => function () {
        $enviromentSettings = [];

        $settings = (require_once __DIR__ . '/enviroment/settings_default.php');

        if (Application::isDevelopment()) {
            $enviromentSettings = require __DIR__ . '/enviroment/settings_development.php';
        }

        if (Application::isHomologation()) {
            $enviromentSettings = require __DIR__ . '/enviroment/settings_homologation.php';
        }

        if (Application::isProduction()) {
            $enviromentSettings = require __DIR__ . '/enviroment/settings_production.php';
        }

        $settings = array_replace_recursive($settings, $enviromentSettings);

        return new Dot($settings);
    },

    App::class => function (ContainerInterface $container) {
        $app = AppFactory::createFromContainer($container);

        // Adding routes of application
        (require __DIR__ . '/../routes/web.php')($app);
        (require __DIR__ . '/../routes/api.php')($app);

        $app->addRoutingMiddleware();

        return $app;
    },

    Twig::class => function (ContainerInterface $container) {
        $settings = $container->get('settings');

        $rootPath = $settings->get('view.path');
        $templates = $settings->get('view.templates');
        $viewSettings = $settings->get('view.settings');
        $twigExtensionsPath = $settings->get('path.slim.twig');

        $loader = new FilesystemLoader([], $rootPath);

        foreach ($templates as $namespace => $template) {
            $loader->addPath($template, $namespace);
        }

        $twig = new Twig($loader, $viewSettings);

        $extensions = \App\Slim\Directory\Directory::turnNameSpacePathIntoArray(
            $twigExtensionsPath,
            "\\App\\Slim\\Twig\\"
        );

        $twig->addExtension(new DebugExtension());
        $twig->addExtension(new IntlExtension());

        foreach ($extensions as $extension) {
            $twig->addExtension(new $extension());
        }

        return $twig;
    },

    RepositoryManager::class => autowire(),

    Illuminate\Database\ConnectionInterface::class => function (ContainerInterface $container) {
        return Manager::connection('default');
    },

    // OAuth
    AuthorizationServer::class => factory([
        SlimAuthorizationServer::class,
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
    },

    Mailer::class => function (ContainerInterface $container) {
        return new MailerPHPMailer();
    }
];
