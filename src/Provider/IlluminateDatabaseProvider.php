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

        $conections = (require_once $container->get('settings')->get('file.database'));

        foreach ($conections as $name => $conection) {
            $manager->addConnection($conection, $name);
        }

        $manager->setAsGlobal();

        $manager->bootEloquent();
    }
}