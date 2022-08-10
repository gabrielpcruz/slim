<?php

namespace App\Console\Migration;

use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RiceDatabase extends ConsoleMigration
{
    /**
     * @return string
     */
    protected function getConnectionName(): string
    {
        return 'sqlite';
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('migration:create-database');
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