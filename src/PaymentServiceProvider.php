<?php

namespace rkujawa\LaravelPaymentGateway;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    public function register()
    {
        
    }
}
