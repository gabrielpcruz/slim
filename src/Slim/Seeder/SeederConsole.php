<?php

namespace App\Slim\Seeder;

use App\App;
use App\Service\Directory\Directory;
use App\Slim\Console\Console;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeederConsole extends Console
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('seeder:seed');
        $this->setDescription('Run the seeders commands');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $seederPath = App::settings()->get('path.seeder');

        $seederPath = Directory::turnNameSpacePathIntoArray(
            $seederPath,
            "App\\Seeder\\",
            [],
            ['Slim']
        );

        $seeders = Directory::getIterator($seederPath);

        foreach ($seeders as $seeder) {
            (new $seeder())->run();
        }

        return Command::SUCCESS;
    }
}
