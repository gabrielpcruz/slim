<?php

namespace App\Console;

use App\Entity\Entity;
use App\Repository\Example\RiceRespository;
use App\Repository\Repository;
use App\Repository\RepositoryManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDatabaseSqlite extends Console
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('file:create-file');
        $this->setDescription('Create the file demo to connect with sqlite.');
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
        $storagePath = $this->container->get('settings')->get('path.storage');
        $databasePath = $storagePath . '/database';

        $sqlitePath = $databasePath . "/db.sqlite";

        file_put_contents($sqlitePath, '');

        return Command::SUCCESS;
    }
}