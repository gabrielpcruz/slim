<?php

namespace App\Console\Slim;

use App\Console\Console;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class NewBusiness extends Console
{
    /**
     * @return void
     */
    protected function configure() : void
    {
        $this->setName('slim:new-business');
        $this->setDescription('Create a full new Business');
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
        $helper = $this->getHelper('question');

        $question = new Question(
            "Digite o nome do Business: ",
        );

        $business = $helper->ask($input, $output, $question);


        $entityPath = $this->getContainer()->get('settings')->get('path.entity');

        $businessName = ucfirst($business);

        // Cria entity
        $newEntityPath = $businessName;
        $command = "mkdir $entityPath/$newEntityPath";

        exec("$command");

        $newEntityName = $businessName;
        $newEntity = "{$newEntityName}";

        $nameSpaceEntity = "App\\Entity\\$businessName";
        $newEntityClass = $this->templateEntity($newEntity, $nameSpaceEntity);


        sleep(1);

        $command = "echo '$newEntityClass' >> {$entityPath}/{$newEntityPath}/{$newEntityPath}Entity.php";
        exec("$command");

        // Cria Repository

        $repositoryPath = $this->getContainer()->get('settings')->get('path.repository');

        $newRepositoryPath = $businessName;
        $command = "mkdir $repositoryPath/$newRepositoryPath";

        exec("$command");

        $newRepositoryName = $businessName;
        $newRepository = "{$newRepositoryName}";

        $nameSpaceRepository = "App\\Repository\\$businessName";
        $newRepositoryClass = $this->templateRepository(
            $newRepository,
            "{$nameSpaceEntity}",
            $newEntity,
            $nameSpaceRepository
        );

        sleep(1);

        $class = "{$repositoryPath}/{$newRepositoryPath}/{$newRepositoryPath}Repository.php";

        $command = "echo '$newRepositoryClass' >> {$class}";
        exec("$command");

        // Cria Business

        $businessPath = $this->getContainer()->get('settings')->get('path.business');

        $newBusinessPath = $businessName;
        $command = "mkdir $businessPath/$newBusinessPath";

        exec("$command");

        $newBusinessName = $businessName;
        $newBusiness = "{$newBusinessName}";

        $nameSpaceBusiness = "App\\Business\\$businessName";
        $newBusinessClass = $this->templateBusiness(
            $newBusiness,
            "{$nameSpaceRepository}",
            $newRepository,
            $nameSpaceBusiness
        );

        sleep(1);

        $command = "echo '$newBusinessClass' >> {$businessPath}/{$newBusinessPath}/{$newBusinessPath}Business.php";
        exec("$command");

        return Command::SUCCESS;
    }

    private function templateEntity($entityName, $namespace): string
    {
        $tableEntity = strtolower($entityName);

        $entity = <<<STR
<?php

namespace $namespace;

use App\Entity\Entity;

class {$entityName}Entity extends Entity
{
    protected DOLAR_table = "$tableEntity";
}
STR;

        return str_replace("DOLAR_", "$", $entity);
    }

    private function templateRepository($repositoryName, $entityNameSpace, $entityName, $namespace) : string
    {
        $entityFull = '\\' . $entityName . "Entity";
        return <<<STR
<?php

namespace $namespace;

use {$entityNameSpace}{$entityFull};
use App\Repository\Repository;

class {$repositoryName}Repository extends Repository
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return {$entityName}Entity::class;
    }
}

STR;
    }

    private function templateBusiness($businessName, $repositoryNameSpace, $repositoryName, $namespace)
    {
        $repositoryFull = '\\' . $repositoryName . "Repository";
        $business = <<<STR
<?php

namespace $namespace;

use App\Business\Business;
use {$repositoryNameSpace}{$repositoryFull};

class {$businessName}Business extends Business
{
    /**
     * @var string
     */
    protected string DOLAR_repositoryClass = {$repositoryName}Repository::class;
}


STR;

        return str_replace("DOLAR_", "$", $business);
    }
}
