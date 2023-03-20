<?php

namespace App\Console;

use DomainException;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Console extends Command
{
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @var InputInterface
     */
    private InputInterface $input;

    /**
     * @var OutputInterface
     */
    private OutputInterface $output;

    /**
     * @param ContainerInterface $container
     * @param string|null $name
     */
    public function __construct(ContainerInterface $container, string $name = null)
    {
        parent::__construct($name);
        $this->container = $container;
    }

    /**
     * @param string $description
     * @return Console
     */
    public function setDescription(string $description): Console
    {
        self::$defaultDescription = $description;

        return parent::setDescription($description);
    }

    /**
     * @param string $name
     * @return Console
     */
    public function setName(string $name): Console
    {
        self::$defaultName = $name;

        return parent::setName($name);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        throw new DomainException("Overide me " . __METHOD__);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        throw new DomainException("Overide me " . __METHOD__);
    }


    /**
     * @param string $message
     * @return void
     */
    protected function info(string $message): void
    {
        $this->output->writeln("<info>{$message}</info>");
    }

    /**
     * @param string $message
     * @return void
     */
    protected function comment(string $message): void
    {
        $this->output->writeln("<comment>{$message}</comment>");
    }

    /**
     * @param string $message
     * @return void
     */
    protected function question(string $message): void
    {
        $this->output->writeln("<question>{$message}</question>");
    }

    /**
     * @param string $message
     * @return void
     */
    protected function error(string $message): void
    {
        $this->output->writeln("<error>{$message}</error>");
    }

    /**
     * @param string $message
     * @param string $url
     * @return void
     */
    protected function link(string $message, string $url): void
    {
        $this->output->writeln("<href='{$url}'>{$message}</href>");
    }

    /**
     * @return void
     */
    protected function breakLine(): void
    {
        $this->output->writeln("");
    }
}
