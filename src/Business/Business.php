<?php

namespace App\Business;

use App\App;
use App\Repository\Repository;
use App\Repository\RepositoryManager;
use DI\DependencyException;
use DI\NotFoundException;
use DomainException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

abstract class Business
{
    /**
     * @var string
     */
    protected string $repositoryClass = '';

    /**
     * @var Repository
     */
    private Repository $respository;

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __construct()
    {
        if (empty($this->repositoryClass)) {
            throw new DomainException("Repository class not defined at attribute '\$this->repositoryClass'");
        }

        $this->container = App::container();
        $this->respository = $this->getRepositoryManager()->get($this->repositoryClass);
    }

    /**
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getRepositoryManager(): RepositoryManager
    {
        return $this->container->get(RepositoryManager::class);
    }

    /**
     * @return Repository
     */
    public function getRepository(): Repository
    {
        return $this->respository;
    }
}