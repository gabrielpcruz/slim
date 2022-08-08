<?php

use App\App;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

$commands = [];

try {
    $excludeClasses = [
        "ConsoleMigration.php",
        "Console.php"
    ];

    $migrationPath = App::settings()->get('path.migration');

    $migrationCommands = [];

    foreach (scandir($migrationPath) as $class) {
        if (!in_array($class, ['.', '..']) && !in_array($class, $excludeClasses)) {
            $migrationCommands[] = "App\\Console\\Migration\\" . str_replace('.php', '', $class);
        }
    }

    $consolePath = App::settings()->get('path.console');

    $consoleCommands = [];

    foreach (scandir($consolePath) as $class) {
        if (!in_array($class, ['.', '..', 'Migration']) && !in_array($class, $excludeClasses)) {
            $consoleCommands[] = "App\\Console\\" . str_replace('.php', '', $class);
        }
    }

    $commands = array_merge($commands, $migrationCommands);
    $commands = array_merge($commands, $consoleCommands);

} catch (
Exception|
NotFoundExceptionInterface|
ContainerExceptionInterface $exception
) {

}


return $commands;