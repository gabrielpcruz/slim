<?php

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use App\App;

try {
    $container = (require_once './config/bootstrap.php')->getContainer();

    if (!App::isConsole()) {
        throw new DomainException('Only CLI allowed. Script stopped.');
    }

    $console = getConsole($container);

    $console->run();

} catch (
Exception|
NotFoundExceptionInterface|
ContainerExceptionInterface $exception
) {
    die($exception->getMessage());
}