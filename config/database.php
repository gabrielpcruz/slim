<?php

use App\App;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

$database = [];

try {
    $storagePath = App::getInstace()->getContainer()->get('settings')->get('path.storage');

    $database = [
        'default' => [
            'driver' => 'sqlite',
            'host' => 'localhost',
            'database' => $storagePath . '/database/db.sqlite',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],
    ];

} catch (
Exception|
NotFoundExceptionInterface|
ContainerExceptionInterface $exception
) {

}

return $database;