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

class ConsoleExample extends Console
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('console:show-example');
        $this->setDescription('Shows example of command console output.');
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
        $this->examplesOutPut($output);

        $this->exampleUsingRepository($output);

        return Command::SUCCESS;
    }

    /**
     * @param OutputInterface $output
     * @return void
     */
    private function examplesOutPut(OutputInterface $output): void
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
    }

    /**
     * @param OutputInterface $output
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function exampleUsingRepository(OutputInterface $output): void
    {
        $output->writeln('');
        $output->writeln('Now, let\'s see an example of command using repository...');
        $output->writeln('');

        sleep(5);

        /** @var Repository $repository */
        $repository = $this->container->get(RepositoryManager::class)->get(RiceRespository::class);

        /** @var Entity $item */
        foreach ($repository->all() as $item) {

            $layout = "<comment>id: <info>%s</info> - name: <info>%s</info></comment>";

            $message = sprintf($layout, $item->id, $item->name);

            $output->writeln($message);
        }
    }
}