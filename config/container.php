<?php

use Slim\App;
use Adbar\Dot;
use Slim\Views\Twig;
use App\App as Application;
use Slim\Factory\AppFactory;
use App\Service\Mail\Mailer;
use App\Twig\GuardTwigExtension;
use App\Twig\GuestTwigExtension;
use App\Twig\AssetsTwigExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use App\Twig\VersionTwigExtension;
use League\OAuth2\Server\CryptKey;
use App\Service\Mail\MailerPHPMailer;
use App\Repository\RepositoryManager;
use App\Twig\FlashMessageTwigExtension;
use Illuminate\Database\Capsule\Manager;
use App\Factory\AuthorizationServerFactory;
use App\Repository\User\AccessTokenRepository;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use Psr\Container\ContainerInterface;

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
        (require __DIR__ . '/routes/web.php')($app);
        (require __DIR__ . '/routes/api.php')($app);

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
        $twig->addExtension(new GuestTwigExtension());
        $twig->addExtension(new GuardTwigExtension());
        $twig->addExtension(new VersionTwigExtension());
        $twig->addExtension(new FlashMessageTwigExtension());

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
    },

    Mailer::class => function (ContainerInterface $container) {
        return new MailerPHPMailer();
    }
];
