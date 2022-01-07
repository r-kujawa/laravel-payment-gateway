<?php

namespace rkujawa\LaravelPaymentGateway;

use Illuminate\Support\ServiceProvider;
use rkujawa\LaravelPaymentGateway\Console\Commands\AddPaymentMerchant;
use rkujawa\LaravelPaymentGateway\Console\Commands\AddPaymentProvider;
use rkujawa\LaravelPaymentGateway\Console\Commands\AddPaymentType;
use rkujawa\LaravelPaymentGateway\Contracts\PaymentGateway;
use rkujawa\LaravelPaymentGateway\Contracts\PaymentManager;
use rkujawa\LaravelPaymentGateway\Contracts\PaymentProcesser;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        if ($this->app->runningInConsole()) {
            $this->vendorPublish();
            $this->commands([
                AddPaymentType::class,
                AddPaymentProvider::class,
                AddPaymentMerchant::class,
            ]);
        }
    }

    protected function vendorPublish()
    {
        $this->publishes([
            __DIR__ . '/config/payment.php' => config_path('payment.php'),
        ], 'payment-config');

        $this->publishes([
            __DIR__ . '/database/migrations/2021_01_01_000000_create_base_payment_tables.php' => database_path('migrations/2021_01_01_000000_create_base_payment_tables.php'),
        ], 'payment-migration');
    }

    public function register()
    {
        $this->app->bind(PaymentGateway::class, function ($app) {
            return new LaravelPaymentGateway();
        });

        $this->app->bind(PaymentManager::class, function ($app) {
            return new LaravelPaymentGateway(PaymentService::MANAGER);
        });

        $this->app->bind(PaymentProcesser::class, function ($app) {
            return new LaravelPaymentGateway(PaymentService::PROCESSOR);
        });
    }
}
