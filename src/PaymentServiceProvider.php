<?php

namespace rkujawa\LaravelPaymentGateway;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }
        $this->registerResources();
    }

    public function register()
    {
        //maybe this is better to initiate on boot method
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
    }

    public function provides()
    {
        return [PaymentGatewayService::class];
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
