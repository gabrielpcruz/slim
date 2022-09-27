<?php

namespace App;

use Adbar\Dot;
use App\Factory\ContainerFactory;
use App\Handler\DefaultErrorHandler;
use App\Provider\ProviderInterface;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\App as SlimApp;
use Exception;

class App
{
    /**
     * @var string
     */
    public const VERSION = '1.0.0';

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
     * @return array|false|string
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
     * @return mixed
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
        $errorMiddleware->setDefaultErrorHandler(DefaultErrorHandler::class);

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
            self::$container = (new ContainerFactory())->create();
        }

        return self::$container;
    }

    /**
     * @param Container $container
     * @param Dot $settings
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    private static function provide(Container $container, Dot $settings)
    {
        $providers = (require_once $settings->get('file.providers'));

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
    private static function defineConstants(Dot $settings)
    {
        define('STORAGE_PATH', $settings->get('path.storage'));
        define('PUBLIC_PATH', $settings->get('path.public'));
    }
}