<?php

use App\App;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

$databaseConnections = [];

try {

    $databasePath = App::settings()->get('path.database');

    foreach (scandir("{$databasePath}/connections") as $connection) {
        if (!in_array($connection, ['.', '..'])) {
            $connectionName = str_replace('.php', '', $connection);
            $connectionSource = (require_once "connections/" . $connection);

            $databaseConnections[$connectionName] = $connectionSource;
        }
    }

    if (!array_key_exists('default', $databaseConnections)) {
        if (empty($databaseConnections)) {
            throw new DomainException('Improve an connections configuration!');
        }

        $databaseConnections['default'] = reset($databaseConnections);
    }

} catch (
Exception|
NotFoundExceptionInterface|
ContainerExceptionInterface $exception
) {

}

return $databaseConnections;