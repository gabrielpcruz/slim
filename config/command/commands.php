<?php

use App\App;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use App\Service\Directory\Directory;

$commands = [];

try {
    $excludeClasses = [
        "ConsoleMigration.php",
        "Console.php",
        "MigrationTrait.php",
        "Migration.php",
        "SeederInterface.php",
        "Seeder.php",
    ];

    $excludePaths = [
        'Migration',
        'Seeder',
        'Slim'
    ];

    // Migration
    $migrationNamespace = "App\\Slim\\Migration\\";
    $migrationPath = App::settings()->get('path.slim.migration');

    $migrationCommands = Directory::turnNameSpacePathIntoArray($migrationPath, $migrationNamespace, $excludeClasses);

    // Seeder
    $seederNamespace = "App\\Slim\\Seeder\\";
    $seederPath = App::settings()->get('path.slim.seeder');

    $seederCommands = Directory::turnNameSpacePathIntoArray($seederPath, $seederNamespace, $excludeClasses);

    // Console
    $consoleCommands = [];
    $consoleNamespace = "App\\Console\\";
    $consolePath = App::settings()->get('path.console');

    $consoleCommands = Directory::turnNameSpacePathIntoArray($consolePath, $consoleNamespace, $excludeClasses, $excludePaths);


    // Slim
    $slimCommands = [];
    $slimNamespace = "App\\Slim\\Console\\";
    $slimPath = App::settings()->get('path.slim.console');

    $slimCommands = Directory::turnNameSpacePathIntoArray($slimPath, $slimNamespace, $excludeClasses, $excludePaths);

    $commands = array_merge($commands, $migrationCommands);
    $commands = array_merge($commands, $seederCommands);
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
