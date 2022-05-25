<?php

namespace App;

use App\Factory\ContainerFactory;
use App\Handler\DefaultErrorHandler;
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

        $settings = $app->getContainer()->get('settings');

        if ($settings->get('error.slashtrace')) {
            $app->getContainer()->get(SlashTrace::class);
        }

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
}