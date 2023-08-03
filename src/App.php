<?php

namespace App;

use Adbar\Dot;
use App\Middleware\Site\Authentication\AuthenticationSite;
use App\Middleware\Site\Maintenance\MaintenanceMiddleware;
use App\Middleware\Site\Maintenance\RoutesInMaintenanceMiddleware;
use App\Provider\ProviderInterface;
use App\Service\Directory\Directory;
use App\Service\Handler\Error;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App as SlimApp;
use Slim\Flash\Messages;

class App
{
    /**
     * @var string
     */
    public const VERSION = '1.3.1';

    /**
     * @var string
     */
    public const DEVELOPMENT = 'DEVELOPMENT';

    /**
     * @var string
     */
    public const PRODUCTION = 'PRODUCTION';

    /**
     * @var string
     */
    public const HOMOLOGATION = 'HOMOLOGATION';

    /**
     * @var SlimApp
     */
    private static SlimApp $app;

    /**
     * @var Container
     */
    private static Container $container;

    /**
     * @var Messages
     */
    private static Messages $flash;

    /**
     * @return bool
     */
    public static function isConsole(): bool
    {
        return self::getType() == 'console';
    }

    /**
     * @return string
     */
    private static function getType(): string
    {
        return php_sapi_name() == 'cli' ? 'console' : 'http';
    }

    /**
     * @return string
     */
    public static function getAppEnv(): string
    {
        return getenv('APP_ENV') ? strtoupper(getenv('APP_ENV')) : self::DEVELOPMENT;
    }

    /**
     * @return bool
     */
    public static function isDevelopment(): bool
    {
        return self::getAppEnv() == self::DEVELOPMENT;
    }

    /**
     * @return bool
     */
    public static function isHomologation(): bool
    {
        return self::getAppEnv() == self::HOMOLOGATION;
    }

    /**
     * @return bool
     */
    public static function isProduction(): bool
    {
        return self::getAppEnv() == self::PRODUCTION;
    }

    /**
     * @return string
     */
    public static function version(): string
    {
        if (self::isProduction()) {
            return App::VERSION;
        }

        return uniqid();
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public static function getInstace()
    {
        if (!isset(self::$app)) {
            self::$app = self::getContainer()->get(SlimApp::class);
        }

        return self::$app;
    }

    /**
     * @return Dot
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    public static function settings(): Dot
    {
        $settings = 'settings';

        return self::getInstace()->getContainer()->get($settings);
    }

    /**
     * @return ContainerInterface
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function container(): ContainerInterface
    {
        return self::getInstace()->getContainer();
    }

    /**
     * @return Messages
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function flash(): Messages
    {
        $flash = 'flash';

        if (!isset(self::$flash)) {
            self::$flash = self::getContainer()->get($flash);
        }

        return self::$flash;
    }

    /**
     * @return SlimApp
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public static function bootstrap(): SlimApp
    {
        $app = self::getInstace();

        $container = $app->getContainer();
        $settings = self::settings();

        self::defineConstants($settings);
        self::provide($container, $settings);

        $errorMiddleware = $app->addErrorMiddleware(true, true, true);
        $errorMiddleware->setDefaultErrorHandler(Error::class);

        if (!App::isConsole()) {
            self::middlewares($app);
        }
        return $app;
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    private static function getContainer(): Container
    {
        if (!isset(self::$container)) {
            self::$container = (new ContainerBuilder())->build();
        }

        return self::$container;
    }

    /**
     * @param Container $container
     * @param Dot $settings
     * @return void
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    private static function provide(Container $container, Dot $settings): void
    {
        $providersPath = self::settings()->get('path.provider');
        $providersNameSpace = "\\App\\Provider\\";

        $providers = Directory::turnNameSpacePathIntoArray(
            $providersPath,
            $providersNameSpace,
            ['ProviderInterface.php']
        );

        /** @var ProviderInterface $provider */
        foreach ($providers as $provider) {
            $provider = new $provider();
            $provider->provide($container, $settings);
        }
    }

    /**
     * @param Dot $settings
     * @return void
     */
    private static function defineConstants(Dot $settings): void
    {
        define('STORAGE_PATH', $settings->get('path.storage'));
        define('PUBLIC_PATH', $settings->get('path.public'));
    }

    /**
     * @param SlimApp $app
     * @return void
     */
    private static function middlewares(SlimApp $app): void
    {
        $app->add(MaintenanceMiddleware::class);
        $app->add(RoutesInMaintenanceMiddleware::class);
        $app->add(AuthenticationSite::class);
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    public static function isGuestRoute(ServerRequestInterface $request): bool
    {
        $guestRoutes = App::settings()->get('system.guest_routes');

        if (in_array($request->getUri()->getPath(), $guestRoutes)) {
            return true;
        }

        return false;
    }

    /**
     * @param ServerRequestInterface $request
     * @param $route
     * @return bool
     */
    public static function isRouteEqualOf(ServerRequestInterface $request, $route): bool
    {
        if ($request->getUri()->getPath() === $route) {
            return true;
        }

        return false;
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    public static function isRouteInMaintenance(ServerRequestInterface $request): bool
    {
        $routesInMaintenance = App::settings()->get('system.routes_in_maintenance');

        if (in_array($request->getUri()->getPath(), $routesInMaintenance)) {
            return true;
        }

        return false;
    }
}
