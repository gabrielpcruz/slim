<?php

namespace App\Provider;

use Adbar\Dot;
use Illuminate\Database\Capsule\Manager;
use Psr\Container\ContainerInterface;

class IlluminateDatabaseProvider implements ProviderInterface
{
    /**
     * @param ContainerInterface $container
     * @param Dot $settings
     * @return void
     */
    public function provide(ContainerInterface $container, Dot $settings)
    {
        $manager = new Manager();

        $conections = (require_once $settings->get('file.database'));

        foreach ($conections as $name => $conection) {
            $manager->addConnection($conection, $name);
        }

        $manager->setAsGlobal();
        $manager->bootEloquent();
    }
}