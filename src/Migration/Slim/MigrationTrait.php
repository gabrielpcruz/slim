<?php

namespace App\Migration\Slim;

use App\App;
use DI\DependencyException;
use DI\NotFoundException;
use Generator;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Builder;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;

trait MigrationTrait
{
    /**
     * @var Builder
     */
    protected Builder $schemaBuilder;

    /**
     * @var Connection
     */
    protected Connection $connection;

    /**
     * @return array
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    private function prepareMigrations(): array
    {
        $pathMigration = App::settings()->get('path.migration');

        $excludeFiles = [];

        $ExcludePaths = [
            'Slim'
        ];

        $migrations = turnNameSpacePathIntoArray(
            $pathMigration,
            "\\App\\Migration\\",
            $excludeFiles,
            $ExcludePaths
        );

        if (!count($migrations)) {
            throw new InvalidArgumentException("None migrations classes found!");
        }

        $migrationsSorted = [];

        foreach ($migrations as $migration) {
            $refletion = new ReflectionClass($migration);
            $class = $refletion->newInstance();
            $order = $class->sortIndex();
            $migrationsSorted[$order] = $class;
        }

        return $migrationsSorted;
    }

    /**
     * @param array $migrationsSorted
     * @return Generator
     */
    public function generator(array $migrationsSorted): Generator
    {
        foreach ($migrationsSorted as $key => $item) {
            yield $key => $item;
        }
    }

    /**
     * @return void
     */
    protected function configureConnection(): void
    {
        $this->connection = Manager::connection($this->getConnectionName());
        $this->schemaBuilder = $this->connection->getSchemaBuilder();
    }

    /**
     * @return Generator
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function migrations(): Generator
    {
        $migrationsSorted = $this->prepareMigrations();

        ksort($migrationsSorted);

        return $this->generator($migrationsSorted);
    }

    /**
     * @return Generator
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function migrationsReverse(): Generator
    {
        $migrationsSorted = $this->prepareMigrations();

        krsort($migrationsSorted);

        return $this->generator($migrationsSorted);
    }
}
