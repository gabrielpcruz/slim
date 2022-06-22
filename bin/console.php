<?php

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use App\App;
use Symfony\Component\Console\Application;

try {
    $container = (require_once './config/bootstrap.php')->getContainer();

    if (!App::isConsole()) {
        throw new DomainException('Only CLI allowed. Script stopped.');
    }

    $commands = (require_once $container->get('settings')->get('file.commands'));

    $console = new Application();

    if (!empty($commands)) {
        foreach ($commands as $commandClass) {
            $console->add($container->get($commandClass));
        }
    }

    $console->run();

} catch (
Exception|
NotFoundExceptionInterface|
ContainerExceptionInterface $exception
) {
    die($exception->getMessage());
}