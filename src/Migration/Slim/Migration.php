<?php

namespace App\Migration\Slim;

use Illuminate\Database\Schema\Blueprint;

abstract class Migration
{
    use MigrationTrait;

    public function __construct()
    {
        $this->configureConnection();
    }

    /**
     * @return void
     */
    abstract public function up(): void;

    /**
     * @return void
     */
    abstract public function down(): void;

    /**
     * @return int
     */
    abstract public function sortIndex(): int;

    /**
     * @return string
     */
    private function getConnectionName(): string
    {
        return 'default';
    }

    protected function timestamps(Blueprint $table): Blueprint
    {
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->useCurrent();

        return $table;
    }
}
