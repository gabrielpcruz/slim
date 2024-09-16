<?php

namespace App\Slim\Repository;

use Illuminate\Database\ConnectionInterface;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

/**
 * Original source
 * @see https://github.com/jerfeson/slim4-skeleton/blob/feature/3.0.0/app/Repository/RepositoryManager.php
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
     * @var AbstractRepository[]
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
     * @param string $repositoryClass
     *
     * @return AbstractRepository
     *
     */
    public function get(string $repositoryClass): AbstractRepository
    {
        if (isset($this->cache[$repositoryClass])) {
            return $this->cache[$repositoryClass];
        }

        if (!class_exists($repositoryClass)) {
            throw new RuntimeException('The repository does not exist!');
        }

        $reflection = new ReflectionClass($repositoryClass);

        if (!$reflection->isSubclassOf(AbstractRepository::class)) {
            throw new RuntimeException('The specified class is not an repository!');
        }

        /** @var AbstractRepository $repository */
        $repository = new $repositoryClass();

        $entityClass = $repository->getEntityClass();

        $repository->setEntity(new $entityClass());
        $repository->setRepositoryManager($this);

        $this->cache[$repositoryClass] = $repository;

        return $repository;
    }
}
