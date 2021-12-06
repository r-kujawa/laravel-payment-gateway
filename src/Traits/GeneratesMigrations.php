<?php

namespace rkujawa\LaravelPaymentGateway\Traits;

use Illuminate\Support\Str;

trait GeneratesMigrations
{
    /**
     * Get the migration path.
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        return database_path('migrations');
    }

    /**
     * Generate the migration file name.
     *
     * @param string $migrationClass
     * @param string $migrationPath
     * @return string
     */
    protected function generateMigrationFileName($class)
    {
        return now()->format('Y_m_d_His') . '_' . Str::snake($class) . '.php';
    }

    /**
     * Generate the full migration file path.
     *
     * @param string $migrationClass
     * @param string $migrationPath
     * @return string
     */
    protected function generateMigrationFilePath($class)
    {
        return "{$this->getMigrationPath()}/{$this->generateMigrationFileName($class)}";
    }
}
