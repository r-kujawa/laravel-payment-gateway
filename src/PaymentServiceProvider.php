<?php

namespace rkujawa\LaravelPaymentGateway;

use Illuminate\Support\ServiceProvider;
use rkujawa\LaravelPaymentGateway\Console\Commands\AddPaymentType;

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

    public function register()
    {

    }

    protected function vendorPublish()
    {
        $this->publishes([
            __DIR__ . '/config/payment.php' => config_path('payment.php'),
        ], 'payment-config');
    }
}
