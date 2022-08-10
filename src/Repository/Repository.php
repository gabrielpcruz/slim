<?php

namespace App\Repository;

use App\Entity\Entity;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Original source @see https://github.com/jerfeson/slim4-skeleton/blob/feature/3.0.0/app/Repository/Repository.php
 *
 * @author Thiago Daher
 */
abstract class Repository
{
    /**
     * @var Entity
     */
    private Entity $entity;

    /**
     * @var RepositoryManager
     */
    private RepositoryManager $repositoryManager;

    /**
     * @return string
     */
    abstract public function getEntityClass(): string;

    /**
     * @param Entity $entity
     */
    public function setEntity(Entity $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @param Entity $entity
     */
    public function save(Entity $entity)
    {
        $entity->save();
    }

    /**
     * @param array $item
     *
     * @return Builder
     */
    public function insert(array $item): Builder
    {
        return $this->query()->create($item);
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->entity->newQuery();
    }

    /**
     * @param $id
     *
     * @return null|Entity|mixed
     */
    public function findById($id): ?Entity
    {
        return $this->query()->find($id);
    }

    /**
     * @return Collection|object[]
     */
    public function all()
    {
        return $this->entity::all();
    }

    /**
     * @param array $params
     * @param array $with
     *
     * @return Builder
     */
    protected function queryWhere(array $params, array $with = []): Builder
    {
        $query = $this->query();

        foreach ($params as $key => $value) {
            $query->where($key, '=', $value);
        }

        if (!empty($with)) {
            $query->with($with);
        }

        return $query;
    }

    /**
     * @return RepositoryManager
     */
    protected function getRepositoryManager(): RepositoryManager
    {
        return $this->repositoryManager;
    }

    /**
     * @param RepositoryManager $repositoryManager
     */
    public function setRepositoryManager(RepositoryManager $repositoryManager): void
    {
        $this->repositoryManager = $repositoryManager;
    }

    /**
     * @return Connection
     */
    protected function getConnection(): Connection
    {
        return $this->entity->getConnection();
    }
}
