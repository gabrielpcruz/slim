<?php

namespace App\Migration;

use App\Migration\Slim\ConsoleMigration;
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
        return 'default';
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('slim:migration-create-demo');
        $this->setDescription('Create de demo database');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->info("Starting migration");

        $this->comment("Creating tables...");
        $this->createTables();

        $this->comment("Inserting data...");
        $this->insertData();

        return Command::SUCCESS;
    }

    private function createTables(): void
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

    private function insertData(): void
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
