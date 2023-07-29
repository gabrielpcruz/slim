<?php

namespace App\Slim\Console\Database;

use App\Slim\Console\Console;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSqlite extends Console
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('slim:create-database-file');
        $this->setDescription('Create the file demo to connect with sqlite.');
        $this->setHidden(true);
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
        $storagePath = $this->getContainer()->get('settings')->get('path.storage');
        $databasePath = $storagePath . '/database';

        $sqlitePath = $databasePath . "/db.sqlite";

        file_put_contents($sqlitePath, '');

        return Command::SUCCESS;
    }
}
