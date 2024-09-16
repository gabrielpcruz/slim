<?php

namespace App\Slim\Seeder;

use App\App;
use DI\DependencyException;
use DI\NotFoundException;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Seeder as IlluminateSeeder;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class AbstractSeeder extends IlluminateSeeder implements SeederInterface
{
    /**
     * @return ConnectionInterface
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    protected function connection(): ConnectionInterface
    {
        return App::container()->get(ConnectionInterface::class);
    }
}
