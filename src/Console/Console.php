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
     * @return void
     */
    protected function configure()
    {
        throw new DomainException("Overide me " . __METHOD__);
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
}