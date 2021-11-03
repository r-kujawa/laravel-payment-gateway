<?php

namespace rkujawa\LaravelPaymentGateway;

use Illuminate\Support\ServiceProvider;
use rkujawa\LaravelPaymentGateway\Console\Commands\AddPaymentProvider;
use rkujawa\LaravelPaymentGateway\Console\Commands\AddPaymentType;
use rkujawa\LaravelPaymentGateway\Interfaces\PaymentGateway;
use rkujawa\LaravelPaymentGateway\Interfaces\PaymentManagement;
use rkujawa\LaravelPaymentGateway\Interfaces\PaymentProcessing;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        if ($this->app->runningInConsole()) {
            $this->vendorPublish();
            $this->commands([
                AddPaymentType::class,
                AddPaymentProvider::class,
            ]);
        }
    }

    protected function vendorPublish()
    {
        $this->publishes([
            __DIR__ . '/config/payment.php' => config_path('payment.php'),
        ], 'payment-config');
    }

    public function register()
    {
        $this->app->bind(PaymentGateway::class, function ($app) {
            return new LaravelPaymentGateway();
        });

        $this->app->bind(PaymentManagement::class, function ($app) {
            return new LaravelPaymentGateway(PaymentService::MANAGER);
        });

        $this->app->bind(PaymentProcessing::class, function ($app) {
            return new LaravelPaymentGateway(PaymentService::PROCESSOR);
        });
    }
}
