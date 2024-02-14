<?php

namespace App\Slim\Migration;

use App\Slim\Console\Console;
use Psr\Container\ContainerInterface;

abstract class ConsoleMigration extends Console
{
    use MigrationTrait;

    /**
     * @param ContainerInterface $container
     * @param string|null $name
     */
    public function __construct(ContainerInterface $container, string $name = null)
    {
        $this->schemaBuilder = null;
        parent::__construct($container, $name);
    }

    /**
     * @return string
     */
    protected function getConnectionName(): string
    {
        return 'default';
    }

    /**
     * @param Migration $migration
     * @return string
     */
    public function getTableName(Migration $migration): string
    {
        return strtolower(get_class($migration));
    }
}
