<?php

namespace App\Service;

use App\App;
use App\Slim\Repository\Repository;
use App\Slim\Repository\RepositoryManager;
use DI\DependencyException;
use DI\NotFoundException;
use DomainException;
use Illuminate\Database\ConnectionInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;


abstract class Service
{
    /**
     * @var RepositoryManager
     */
    private RepositoryManager $repositoryManager;

    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        $this->repositoryManager = App::container()->get(RepositoryManager::class);
    }

    /**
     * @param string|null $repositoryClass
     * @return Repository
     */
    final public function getRepository(string $repositoryClass = null): Repository
    {
        if (!$repositoryClass) {
            $repositoryClass = $this->getRepositoryClass();
        }

        return $this->repositoryManager->get($repositoryClass);
    }

    /**
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        throw new DomainException(
            "Override the method 'getRepositoryClass' providing the corresponding class!"
        );
    }

    /**
     * @throws Throwable
     */
    final protected function beginTransaction(): void
    {
        $this->getConnection()->beginTransaction();
    }

    /**
     * @return ConnectionInterface
     */
    final protected function getConnection(): ConnectionInterface
    {
        return $this->repositoryManager->getConnection();
    }

    /**
     * @throws Throwable
     */
    final protected function rollBack(): void
    {
        $this->getConnection()->rollBack();
    }

    /**
     * @throws Throwable
     */
    final protected function commit(): void
    {
        $this->getConnection()->commit();
    }

    /**
     * @return RepositoryManager
     */
    final protected function getRepositoryManager(): RepositoryManager
    {
        return $this->repositoryManager;
    }
}
