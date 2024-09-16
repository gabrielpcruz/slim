<?php

namespace App;

use App\Slim\Container\DefaultContainer;
use App\Slim\Directory\Directory;
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
        return (new ContainerBuilderDI())
            ->addDefinitions((new DefaultContainer())->getDefinitions())
            ->addDefinitions([
                'flash' => function () {
                    return new Messages($_SESSION);
                }
            ])
            ->addDefinitions($this->getDefinitions())
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
