<?php

namespace App\Factory;

use DI\ContainerBuilder;
use DI\Container;
use Exception;

final class ContainerFactory
{
    /**
     * @return Container
     * @throws Exception
     */
    public function createInstance(): Container
    {
        $containerBuilder = new ContainerBuilder();

        // Container's definitions
        $containers = require __DIR__ . '/../../config/container.php';


        $definitions = array_merge($containers, []);

        $containerBuilder->addDefinitions($definitions);

        return $containerBuilder->build();
    }
}