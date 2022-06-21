<?php

namespace App\Provider;

use Illuminate\Database\Capsule\Manager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class IlluminateDatabaseProvider implements ProviderInterface
{
    /**
     * @param ContainerInterface $container
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function provid(ContainerInterface $container)
    {
        $manager = new Manager();

        $database = $container->get('settings')->get('database.sqlite');

        $manager->addConnection($database);

        $manager->setAsGlobal();

        $manager->bootEloquent();
    }
}