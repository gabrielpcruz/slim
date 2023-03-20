<?php

namespace App\Console\Slim\Oauth;

use App\Console\Console;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateServerKeys extends Console
{
    /**
     * @var string
     */
    private string $privateCommand = 'openssl genrsa -out %s 2048';

    /**
     * @var string
     */
    private string $publicCommand = 'openssl rsa -in %s -pubout -out %s';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('oauth2:create-server-keys');
        $this->setDescription('Create the keys of oauth2.');
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
        /**
         * This is an example how to get answer from the cli user.
         */

        /**
         * $helper = $this->getHelper('question');
         *
         * $bundles = ['yes', 'Yes', 'no', 'No'];
         *
         * $question = new Question(
         * "Do you want generate the key's to Oauth2?(Yes/No)" . PHP_EOL,
         * 'No'
         * );
         *
         * $question->setAutocompleterValues($bundles);
         *
         * $generate = $helper->ask($input, $output, $question);
         *
         * if (strtolower($generate) === 'yes') {
         * $this->generateKeys();
         * }
         **/

        $this->generateKeys();

        return Command::SUCCESS;
    }

    /**
     * @return void
     * @throws NotFoundExceptionInterface
     *
     * @throws ContainerExceptionInterface
     */
    private function generateKeys(): void
    {
        $privateKeyPath = $this->getContainer()->get('settings')->get('file.oauth_private');
        $publicKeyPath = $this->getContainer()->get('settings')->get('file.oauth_public');

        $privateKey = sprintf($this->privateCommand, $privateKeyPath);

        exec($privateKey);

        sleep(3);

        $publicKey = sprintf($this->publicCommand, $privateKeyPath, $publicKeyPath);

        exec($publicKey);

        sleep(3);
    }
}
