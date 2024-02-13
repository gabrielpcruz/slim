<?php

use App\App;
use App\Slim\Directory\Directory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
        'Slim'
    ];

    // Seeder
    $seederNamespace = "App\\Slim\\Seeder\\";
    $seederPath = App::settings()->get('path.slim.seeder');

    $seederCommands = Directory::turnNameSpacePathIntoArray(
        $seederPath,
        $seederNamespace,
        $excludeClasses
    );

    // Console
    $consoleCommands = [];
    $consoleNamespace = "App\\Console\\";
    $consolePath = App::settings()->get('path.console');

    $consoleCommands = Directory::turnNameSpacePathIntoArray(
        $consolePath,
        $consoleNamespace,
        $excludeClasses,
        $excludePaths
    );

    $commands = array_merge($commands, $seederCommands);
    $commands = array_merge($commands, $consoleCommands);

    // Slim
    $slimCommands = [];
    $slimNamespace = "App\\Slim\\Console\\";
    $slimPaths = App::settings()->get('path.slim.console');


    foreach ($slimPaths as $key => $slimPath) {
        $namespace = Directory::turnPathIntoNameSpace($slimPath);
        $arr = Directory::turnNameSpacePathIntoArray(
            $slimPath,
            $namespace,
            $excludeClasses,
            $excludePaths
        );

        $commands = array_merge($commands, $arr);
    }


} catch (
Exception|
NotFoundExceptionInterface|
ContainerExceptionInterface $exception
) {

    $commands = [];
}

return $commands;
