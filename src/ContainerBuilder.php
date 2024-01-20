<?php

namespace App;

use App\Service\Directory\Directory;
use DI\Container;
use DI\ContainerBuilder as ContainerBuilderDI;
use Exception;
use Slim\Flash\Messages;

use function DI\autowire;

final class ContainerBuilder
{
    /**
     * @return Container
     * @throws Exception
     */
    public function build(): Container
    {
        $this->getDefinitions();
        return (new ContainerBuilderDI())
            ->addDefinitions(__DIR__ . '/../config/container.php')
            ->addDefinitions([
                'flash' => function () {
                    return new Messages($_SESSION);
                }
            ])
            ->addDefinitions($this->getDefinitions())
//            ->enableDefinitionCache()
            ->enableCompilation(__DIR__ . '/../storage/cache/container')
            ->writeProxiesToFile(true, __DIR__ . '/../storage/cache/proxy')
            ->build();
    }

    /**
     * @return array
     */
    private function getDefinitions(): array
    {
        $definitions = [];

        $controllers = Directory::turnNameSpacePathIntoArray(
            __DIR__ . '/../src/Http',
            "\\App\\Http\\",
            ["Controller.php"]
        );

        foreach ($controllers as $controller) {
            $definitions[$controller] = autowire();
        }


        return $definitions;
    }
}
