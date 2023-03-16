<?php

use App\App;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

$commands = [];

try {
    $excludeClasses = [
        "ConsoleMigration.php",
        "Console.php",
        "MigrationTrait.php",
        "Migration.php",
    ];

    $excludePaths = [
        'Migration',
        'Slim'
    ];

    // Migration
    $migrationNamespace = "App\\Migration\\Slim\\";
    $migrationPath = App::settings()->get('path.migration') . '/Slim';

    $migrationCommands = turnNameSpacePathIntoArray($migrationPath, $migrationNamespace, $excludeClasses);

    // Console
    $consoleCommands = [];
    $consoleNamespace = "App\\Console\\";
    $consolePath = App::settings()->get('path.console');

    $consoleCommands = turnNameSpacePathIntoArray($consolePath, $consoleNamespace, $excludeClasses, $excludePaths);


    // Slim
    $slimCommands = [];
    $slimNamespace = "App\\Console\\Slim\\";
    $slimPath = App::settings()->get('path.slim');

    $slimCommands = turnNameSpacePathIntoArray($slimPath, $slimNamespace, $excludeClasses, $excludePaths);

    $commands = array_merge($commands, $migrationCommands);
    $commands = array_merge($commands, $consoleCommands);
    $commands = array_merge($commands, $slimCommands);

} catch (
Exception|
NotFoundExceptionInterface|
ContainerExceptionInterface $exception
) {
    $commands = [];
}


return $commands;
