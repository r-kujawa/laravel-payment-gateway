<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use Illuminate\Filesystem\Filesystem;

abstract class CommandTestCase extends TestCase
{
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
