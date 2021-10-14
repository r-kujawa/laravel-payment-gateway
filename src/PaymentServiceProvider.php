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
            $this->registerPublishing();
            $this->commands([
                AddPaymentType::class,
            ]);
        }
    }

    public function register()
    {

    }

    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../config/paymentToPublish.php' => config_path('payment.php')
        ], 'payments-config');
    }
}
