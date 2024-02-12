<?php

namespace App\Console;

use App\Slim\Console\Console;
use App\Slim\Mail\Mailer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Views\Twig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EmailCommandExample extends Console
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('app:console:email-example');
        $this->setDescription('Sends an email example from command console.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Mailer $mailer */
        $mailer = $this->getContainer()->get(Mailer::class);

        /** @var Twig $twig */
        $twig = $this->getContainer()->get(Twig::class);

        $body = $twig->fetch(
            '@email/email_example.twig',
            [
                'title' => 'This is an example of title'
            ]
        );

        $testNumber = uniqid();

        $mailer
            ->setFrom('emailfrom@example.com')
            ->setRecipient('emailecipient@example.com')
            ->subject("Test of email, number ({$testNumber})")
            ->isHtml(true)
            ->body($body)
            ->send();

        return Command::SUCCESS;
    }
}
