<?php

namespace App;

use App\Factory\ContainerFactory;
use App\Handler\DefaultErrorHandler;
use App\Provider\ProviderInterface;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SlashTrace\SlashTrace;
use Slim\App as SlimApp;
use Exception;
use Slim\Handlers\ErrorHandler;

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
     * @return SlimApp
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function bootstrap(): SlimApp
    {
        $app = self::getInstace();

        self::provide();

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
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    private static function provide()
    {
        $container = self::getContainer();

        $providers = (require_once $container->get('settings')->get('file.providers'));

        /** @var ProviderInterface $provider */
        foreach ($providers as $provider) {
            $provider = new $provider();
            $provider->provid($container);
        }
    }
}