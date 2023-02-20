<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use rkujawa\LaravelPaymentGateway\PaymentServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
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

    /**
     * Perform any work that should take place once the database has finished refreshing.
     *
     * @return void
     */
    protected function afterRefreshingDatabase()
    {
        if (! class_exists('CreateBasePaymentTables')) {
            $this->artisan('vendor:publish', ['--provider' => 'rkujawa\LaravelPaymentGateway\PaymentServiceProvider', '--tag' => 'migrations']);

            $this->artisan('migrate');
        }
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->artisan('config:clear');

        parent::tearDown();
    }
}
