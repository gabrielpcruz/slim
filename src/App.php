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
use Psr\Container\NotFoundExceptionInterface;
use Slim\App as SlimApp;
use Exception;

class App
{
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
        return self::getInstace()->getContainer()->get('settings');
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
        $settings = $container->get('settings');

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
    }
}