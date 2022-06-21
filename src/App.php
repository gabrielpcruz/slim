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
use Slim\App as SlimApp;
use Exception;
use Symfony\Component\Console\Application;

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
    public static function getType(): string
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

        self::provide();

        if (self::isConsole()) {
            self::runCommands();
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

    /**
     * @throws Exception
     */
    private static function runCommands()
    {
        $commands = (require_once self::getContainer()->get('settings')->get('file.commands'));

        $console = new Application();

        if (empty($commands)) {
            exit(0);
        }

        foreach ($commands as $commandClass) {
            $console->add(self::$container->get($commandClass));
        }

        exit($console->run());
    }
}