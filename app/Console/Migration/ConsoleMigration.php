<?php

namespace App\Console\Migration;

use App\Console\Console;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Builder;
use Psr\Container\ContainerInterface;

abstract class ConsoleMigration extends Console
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
     * @param ContainerInterface $container
     * @param string|null $name
     */
    public function __construct(ContainerInterface $container, string $name = null)
    {
        parent::__construct($container, $name);
        $this->connection = Manager::connection($this->getConnectionName());
        $this->schemaBuilder = $this->connection->getSchemaBuilder();
    }

    /**
     * @return string
     */
    abstract protected function getConnectionName(): string;
}