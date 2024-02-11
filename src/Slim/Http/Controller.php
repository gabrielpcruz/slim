<?php

namespace App\Slim\Http;

use App\App;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Flash\Messages;
use App\Slim\Repository\RepositoryManager;

abstract class Controller
{
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return RepositoryManager
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getRepositoryManager(): RepositoryManager
    {
        return $this->container->get(RepositoryManager::class);
    }

    /**
     * @return Messages
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function flash(): Messages
    {
        return App::flash();
    }
}
