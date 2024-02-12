<?php

namespace App\Slim\Console\Migration;

use App\Slim\Migration\ConsoleMigration;
use App\Slim\Migration\Migration;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Reset extends ConsoleMigration
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('migration:slim:reset');
        $this->setDescription('Drop all tables of database and run all migrations.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->comment("Droping tables...");

            /** @var Migration $migration */
            foreach ($this->migrationsReverse() as $migration) {
                $this->info("{$this->getTableName($migration)}...");

                $migration->down();
            }

            $this->breakLine();

            $this->comment("Creating tables...");

            /** @var Migration $migration */
            foreach ($this->migrations() as $migration) {
                $this->info("table {$this->getTableName($migration)}...");

                $migration->up();
            }
        } catch (Exception $exception) {
            $this->error($exception->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
