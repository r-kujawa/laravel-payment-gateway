<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use Illuminate\Filesystem\Filesystem;

abstract class CommandTestCase extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        (new Filesystem)->cleanDirectory(database_path('migrations'));

        $this->artisan('migrate');
    }

    protected function getArgumentQuestion($model)
    {
        return "What payment {$model} would you like to add?";
    }

    protected function getOptionQuestion($option, $model, $name)
    {
        return "What {$option} would you like to use for the {$name} payment {$model}?";
    }

    protected function getMigrationOutput($model, $name)
    {
        return "The migration to add {$name} payment {$model} has been generated.";
    }

    protected function getMigrationConfirmation()
    {
        return "Would you like to run the migration?";
    }
}
