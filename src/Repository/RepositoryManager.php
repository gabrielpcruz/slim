<?php

namespace App\Repository;

use Illuminate\Database\ConnectionInterface;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

/**
 * Original source @see https://github.com/jerfeson/slim4-skeleton/blob/feature/3.0.0/app/Repository/RepositoryManager.php
 *
 * @author Thiago Daher
 */
class RepositoryManager
{
    /**
     * @var ConnectionInterface
     */
    private ConnectionInterface $connection;

    /**
     * @var Repository[]
     */
    private array $cache;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function setConnection(ConnectionInterface $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * @param string $repositoryClass
     *
     * @return object|Repository
     * @throws ReflectionException
     *
     */
    public function get(string $repositoryClass): Repository
    {
        if (isset($this->cache[$repositoryClass])) {
            return $this->cache[$repositoryClass];
        }

        if (!class_exists($repositoryClass)) {
            throw new RuntimeException('The repository does not exist!');
        }

        $reflection = new ReflectionClass($repositoryClass);

        if (!$reflection->isSubclassOf(Repository::class)) {
            throw new RuntimeException('The specified class is not an repository!');
        }

        $repository = new $repositoryClass();

        $entityClass = $repository->getEntityClass();

        $repository->setEntity(new $entityClass());
        $repository->setRepositoryManager($this);

        $this->cache[$repositoryClass] = $repository;

        return $repository;
    }
}
