<?php

namespace rkujawa\LaravelPaymentGateway;

use Illuminate\Support\ServiceProvider;
use rkujawa\LaravelPaymentGateway\Console\Commands\AddPaymentType;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
            $this->commands([
                AddPaymentType::class,
            ]);
        }

        $this->registerResources();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/payment.php', 'payment'
        );
        $this->app->bind(PaymentGatewayService::class, function ($app, $config = null) {
            if ($config) {
                $paymentGateway = PaymentGatewayFactory::create(
                    optional($config['paymentProvider'])->name,
                    $config['useSandbox']
                );
                $paymentGatewayService = new PaymentGatewayService($paymentGateway);
                $paymentGatewayService->setMerchant($config['merchant']);
                return $paymentGatewayService;
            }

            return new PaymentGatewayService(PaymentGatewayFactory::create());
        });

        $this->app->singleton('PaymentService', function ($app) {
            return new PaymentGatewayService(PaymentGatewayFactory::create());
        });
    }

    private function registerResources()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../config/paymentToPublish.php' => config_path('payment.php')
        ], 'payments-config');
    }
}
