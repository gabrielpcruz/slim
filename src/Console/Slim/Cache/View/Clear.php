<?php

namespace App\Console\Slim\Cache\View;

use App\Console\Console;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Clear extends Console
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('slim:view-clear');
        $this->setDescription('Clear the view cache.');
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
        $cacheView = $this->getContainer()->get('settings')->get('view.settings.cache');

        exec("rm -rf $cacheView/*");

        return Command::SUCCESS;
    }
}
