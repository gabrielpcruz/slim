<?php

namespace App\Console\Migration;

use App\Console\Console;
use DateTime;
use Illuminate\Database\Connection;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Builder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RiceDatabase extends Console
{
    /**
     * @var Builder
     */
    private Builder $schemaBuilder;

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('app:rice-database');
        $this->setDescription('Create de demo database');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Starting migration</info>');

        $this->connection = Manager::connection('default');
        $this->schemaBuilder = $this->connection->getSchemaBuilder();

        $output->writeln('<comment>Creating tables...</comment>');
        $this->createTables();

        $output->writeln('<comment>Inserting data...</comment>');
        $this->insertData();

        return Command::SUCCESS;
    }

    private function createTables()
    {
        if (!$this->schemaBuilder->hasTable('rice')) {
            $this->schemaBuilder->create('rice', function ($table) {
                $table->increments('id')->unsigned();
                $table->string('name', 255)->unique();
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
                $table->softDeletes('deleted_at', 0);
            });
        }
    }

    private function insertData()
    {
        $date = new DateTime();

        for ($i = 0; $i < 100; $i++) {
            $this->connection->table('rice')->insert([
                'name' => 'rice_name_' . uniqid(),
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}