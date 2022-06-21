<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleExample extends Console
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('app:example');
        $this->setDescription('Example command console');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = "<info>Hello, I'm console command.</info>";
        $output->writeln($message);

        return Command::SUCCESS;
    }
}