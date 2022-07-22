<?php

namespace rkujawa\LaravelPaymentGateway;

use rkujawa\LaravelPaymentGateway\Contracts\Merchantable;
use rkujawa\LaravelPaymentGateway\Contracts\Providable;

abstract class PaymentServiceDriver
{
    /**
     * Resolve the providable instance.
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Providable|string|int $provider
     * @return \rkujawa\LaravelPaymentGateway\Contracts\Providable|null
     */
    abstract public function resolveProvider($provider);

    /**
     * Get the default providable identifier (slug or id).
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Merchantable|null $merchant
     * @return string|int
     */
    public function getDefaultProvider(Merchantable $merchant = null)
    {
        return config('payment.defaults.provider');
    }

    /**
     * Resolve the merchantable intance.
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Merchantable|string|int $merchant
     * @return \rkujawa\LaravelPaymentGateway\Contracts\Merchantable|null
     */
    abstract public function resolveMerchant($merchant);

    /**
     * Get the default merchantable identifier (slug or id).
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Providable|null $provider
     * @return string|int
     */
    public function getDefaultMerchant(Providable $provider = null)
    {
        return config('payment.defaults.merchant');
    }

    /**
     * Verify that the merchant is compatible with the provider.
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Merchantable
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Providable
     * @return bool
     */
    abstract public function check($merchant, $provider);

    /**
     * Resolve the gateway class.
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Providable $provider
     * @return string
     */
    abstract public function resolveGatewayClass($provider);
}
