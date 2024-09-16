<?php

namespace App\Slim\Container;

use App\App;
use App\Repository\User\AccessTokenAbstractRepository;
use App\Service\Token\AuthorizationServer;
use App\Slim\Directory\Directory;
use App\Slim\Repository\RepositoryManager;
use Illuminate\Database\Capsule\Manager;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use League\OAuth2\Server\CryptKey;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Twig\Extension\DebugExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;
use App\Service\Token\AuthorizationServer as SlimAuthorizationServer;
use Illuminate\Database\ConnectionInterface;
use function DI\factory;
use function DI\autowire;

class DefaultContainer implements SlimContainerApp
{
    /**
     * @return array
     */
    public function getDefinitions(): array
    {
        return [
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

                $extensions = Directory::turnNameSpacePathIntoArray(
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

            ConnectionInterface::class => function (ContainerInterface $container) {
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

                /** @var AccessTokenAbstractRepository $accessTokenRepository */
                $accessTokenRepository = $repositoryManager->get(AccessTokenAbstractRepository::class);

                $beareValidator = new BearerTokenValidator($accessTokenRepository);
                $beareValidator->setPublicKey(new CryptKey($oauth2PublicKey));

                return $beareValidator;
            },
        ];
    }
}
