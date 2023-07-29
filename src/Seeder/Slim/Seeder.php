<?php

namespace App\Seeder\Slim;

use App\App;
use DI\DependencyException;
use DI\NotFoundException;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Seeder as IlluminateSeeder;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Seeder extends IlluminateSeeder
{
    /**
     * @return ConnectionInterface
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    protected function connection() : ConnectionInterface
    {
        return App::container()->get(ConnectionInterface::class);
    }
}
