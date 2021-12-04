<?php

namespace rkujawa\LaravelPaymentGateway\Traits;

use Illuminate\Support\Str;

trait GeneratesMigrations
{
    /**
     * Generate the full migration file path.
     *
     * @param string $migrationClass
     * @param string $migrationPath
     * @return string
     */
    protected function generateMigrationFilePath($class, $path)
    {
        $fileName = now()->format('Y_m_d_His') . '_' . Str::snake($class) . '.php';

        return "{$path}/{$fileName}";
    }
}
