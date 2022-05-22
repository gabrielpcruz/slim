<?php

namespace App;

use App\Factory\ContainerFactory;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
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
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public static function getContainer(): Container
    {
        if (!isset(self::$container)) {
            self::$container = (new ContainerFactory())->createInstance();
        }

        return self::$container;
    }
}