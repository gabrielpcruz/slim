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
    public function create(): Container
    {
        $containerBuilder = new ContainerBuilder();

        $containerBuilder->addDefinitions(__DIR__ . '/../../config/container.php');

        return $containerBuilder->build();
    }
}