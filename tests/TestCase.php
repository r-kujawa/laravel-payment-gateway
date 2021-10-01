<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use Orchestra\Testbench\Factories\UserFactory;
use rkujawa\LaravelPaymentGateway\PaymentServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public $buyer;

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

    protected function setUp(): void
    {
        parent::setUp();
        $this->buyer = new BuyerHelper();
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../src/database/migrations');
        $this->loadLaravelMigrations(['--database' => 'payments_test']);
    }
}
