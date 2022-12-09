<?php

namespace App;

use DI\Container;
use DI\ContainerBuilder as ContainerBuilderDI;
use Exception;
use Slim\Flash\Messages;

final class ContainerBuilder
{
    /**
     * @return Container
     * @throws Exception
     */
    public function build(): Container
    {
        return (new ContainerBuilderDI())
            ->addDefinitions(__DIR__ . '/../config/container.php')
            ->addDefinitions([
                'flash' => function () {
                    return new Messages($_SESSION);
                }
            ])
            ->build();
    }
}
