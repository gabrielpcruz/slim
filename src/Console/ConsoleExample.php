<?php

namespace App\Console;

use App\Entity\Example\RiceEntity;
use App\Repository\Example\RiceRespository;
use App\Slim\Console\Console;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use App\Slim\Repository\Repository;
use App\Slim\Repository\RepositoryManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleExample extends Console
{
    /**
     * @return void
     */
    protected function configure(): void
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
        $this->info("Hello, I'm console command Info.");

        $this->comment("Hello, I'm console command Comment.");

        $this->question("Hello, I'm console Question.");

        $this->error("Hello, I'm console command Error.");

        $this->link(
            "I'm a example of link on console. Hold CTRL + Click-me",
            'https://github.com/gabrielpcruz/slim'
        );
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
        $repository = $this->getContainer()->get(RepositoryManager::class)->get(RiceRespository::class);

        /** @var RiceEntity $item */
        foreach ($repository->all() as $item) {
            $layout = "<comment>id: <info>%s</info> - name: <info>%s</info></comment>";

            $message = sprintf($layout, $item->id(), $item->attribute('name'));

            $output->writeln($message);
        }
    }
}
