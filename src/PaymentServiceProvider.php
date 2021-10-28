<?php

namespace rkujawa\LaravelPaymentGateway;

use Illuminate\Support\ServiceProvider;
use rkujawa\LaravelPaymentGateway\Console\Commands\AddPaymentType;
use rkujawa\LaravelPaymentGateway\Interfaces\PaymentManager;
use rkujawa\LaravelPaymentGateway\Interfaces\PaymentProcessor;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        if ($this->app->runningInConsole()) {
            $this->vendorPublish();
            $this->commands([
                AddPaymentType::class,
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
            return new PaymentGateway(PaymentService::FULL);
        });

        $this->app->bind(PaymentProcessor::class, function ($app) {
            return new PaymentService(PaymentService::PROCESSOR);
        });

        $this->app->bind(PaymentManager::class, function ($app) {
            return new PaymentService(PaymentService::MANAGER);
        });
    }
}
