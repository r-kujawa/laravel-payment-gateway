<?php

namespace rkujawa\LaravelPaymentGateway;

use rkujawa\LaravelPaymentGateway\Contracts\GatewayRequest;
use rkujawa\LaravelPaymentGateway\Providers\Fake\FakeGateway;

class PaymentGatewayFactory
{
    public static function allowedProviders(): array
    {
        $allowedProviders = config('payment.providers');

        return array_map(function ($provider) {
            return $provider['class'];
        }, $allowedProviders);
    }

    /**
     * Creates a new GatewayRequest
     *
     * @param string|null $provider The chosen provider
     * @return GatewayRequest
     * @throws Exception Unsupported Provider
     */
    public static function create(?string $provider = null, bool $useSandbox = false): GatewayRequest
    {
        if (!isset($provider)) {
            $provider = config('payment.defaults.provider');
        }

        $providers = self::allowedProviders();

        if (!array_key_exists($provider, $providers)) {
            throw new \Exception('Provider not supported.');
        }

        /*if (config('app.env') === 'testing' && !$useSandbox) {
            return new FakeGateway($provider);
        }*/

        return new $providers[$provider];
    }
}
