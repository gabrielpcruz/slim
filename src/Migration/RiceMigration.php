<?php

namespace App\Migration;

use App\Slim\Migration\Migration;

class RiceMigration extends Migration
{
    public function up(): void
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

    /**
     * @return void
     */
    public function down(): void
    {
        $this->schemaBuilder->drop('rice');
    }

    /**
     * @return int
     */
    public function sortIndex(): int
    {
        return 1;
    }
}
