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

class Down extends ConsoleMigration
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('migration:slim:down');
        $this->setDescription('Drop all tables of database.');
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
                $this->info("table {$this->getTableName($migration)}...");

                $migration->down();
            }
        } catch (Exception $exception) {
            $this->error($exception->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
