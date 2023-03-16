<?php

namespace App\Migration\Slim;

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
        $this->setName('migration:reset');
        $this->setDescription('Reset the database and run all migrations');
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
                $this->info("{$this->getTableNome($migration)}...");

                $migration->down();
            }

            $this->breakLine();

            $this->comment("Creating tables...");

            /** @var Migration $migration */
            foreach ($this->migrations() as $migration) {

                $this->info("table {$this->getTableNome($migration)}...");

                $migration->up();
            }

        } catch (Exception $exception) {
            $this->error($exception->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
