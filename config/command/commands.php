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

    $excludePaths = [
        'Migration'
    ];

    // Migration
    $migrationNamespace = "App\\Console\\Migration\\";
    $migrationPath = App::settings()->get('path.migration');

    $migrationCommands = turnNameSpacePathIntoArray($migrationPath, $migrationNamespace, $excludeClasses);

    // Console
    $consoleCommands = [];
    $consoleNamespace = "App\\Console\\";
    $consolePath = App::settings()->get('path.console');

    $consoleCommands = turnNameSpacePathIntoArray($consolePath, $consoleNamespace, $excludeClasses, $excludePaths);

    $commands = array_merge($commands, $migrationCommands);
    $commands = array_merge($commands, $consoleCommands);

} catch (
Exception|
NotFoundExceptionInterface|
ContainerExceptionInterface $exception
) {
    $commands = [];
}


return $commands;