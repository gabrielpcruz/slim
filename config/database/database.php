<?php

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

$database = [];

try {
    $database = [
            'default' => (require_once 'connections/sqlite.php'),
    ];

} catch (
Exception|
NotFoundExceptionInterface|
ContainerExceptionInterface $exception
) {

}

return $database;