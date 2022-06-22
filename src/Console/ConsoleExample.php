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
        $message = "<info>Hello, I'm console command Info.</info>";

        $output->writeln($message);

        $message = "<comment>Hello, I'm console command Comment.</comment>";

        $output->writeln($message);

        $message = "<question>Hello, I'm console Question.</question>";

        $output->writeln($message);

        $message = "<error>Hello, I'm console command Error.</error>";

        $output->writeln($message);

        $link = 'https://github.com/gabrielpcruz/slim';

        $message = "<href={$link}>I'm a example of link on console. Hold CTRL + Click-me</>";

        $output->writeln($message);

        return Command::SUCCESS;
    }
}