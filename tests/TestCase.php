<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use Illuminate\Filesystem\Filesystem;
use rkujawa\LaravelPaymentGateway\PaymentServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
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

        $this->artisan('vendor:publish', ['--tag' => 'payment-migration']);

        $this->artisan('migrate');
    }

    protected function getPackageProviders($app)
    {
        return [
            PaymentServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'payments_test');
        $app['config']->set('database.connections.payments_test', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
    }
}
