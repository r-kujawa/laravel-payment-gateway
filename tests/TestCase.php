<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use rkujawa\LaravelPaymentGateway\PaymentServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
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
