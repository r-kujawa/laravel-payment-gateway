<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use rkujawa\LaravelPaymentGateway\Database\Seeders\PaymentTypeSeeder;
use rkujawa\LaravelPaymentGateway\PaymentServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{

    public $buyer;
    /**
     * Setup the test environment.
     *
     * @return void
     */
    /*protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
    }*/


    protected function getPackageProviders($app)
    {
        return [PaymentServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'payments_test');
        $app['config']->set('database.connections.payments_test', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->buyer = new BuyerHelper();
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations(['--database' => 'payments_test']);
        $this->loadMigrationsFrom(__DIR__.'/../src/database/migrations');
    }

    protected function defineDatabaseSeeders(): void
    {
        $this->seed(PaymentTypeSeeder::class);
    }
}
