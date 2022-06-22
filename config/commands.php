<?php

use App\App;
use App\Console\ConsoleExample;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

$commands = [];

try {
    $migrationPath = App::getInstace()->getContainer()->get('settings')->get('path.migration');

    $migrationCommands = [];

    foreach (scandir($migrationPath) as $class) {
        if (!in_array($class, ['.', '..'])) {
            $migrationCommands[] = "App\\Console\\Migration\\" . str_replace('.php', '', $class);
        }
    }

    $commands = [
        ConsoleExample::class
    ];

    $commands = array_merge($commands, $migrationCommands);

} catch (
Exception|
NotFoundExceptionInterface|
ContainerExceptionInterface $exception
) {

}


return $commands;