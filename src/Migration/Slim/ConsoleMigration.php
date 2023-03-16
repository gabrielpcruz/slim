<?php

namespace App\Migration\Slim;

use App\Console\Console;
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
        parent::__construct($container, $name);
        $this->configureConnection();
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
    public function getTableNome(Migration $migration): string
    {
        return strtolower(get_class($migration));
    }
}
