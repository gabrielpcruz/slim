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

class Migrate extends ConsoleMigration
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('migration:slim:migrate');
        $this->setDescription('Run all migrations on database presents on "App\\Migration" namespace.');
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
