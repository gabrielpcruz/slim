<?php

namespace App\Business;

use App\Repository\Repository;
use App\Repository\RepositoryManager;
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
     * @param ContainerInterface $container
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        if (empty($this->repositoryClass)) {
            throw new DomainException("Repository class not defined at attribute '\$this->repositoryClass'");
        }

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