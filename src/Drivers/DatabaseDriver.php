<?php

namespace rkujawa\LaravelPaymentGateway\Drivers;

use rkujawa\LaravelPaymentGateway\Contracts\Merchantable;
use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;
use rkujawa\LaravelPaymentGateway\PaymentServiceDriver;

class DatabaseDriver extends PaymentServiceDriver
{
    /**
     * Resolve the providable instance.
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Providable|string $provider
     * @return \rkujawa\LaravelPaymentGateway\Contracts\Providable|null
     */
    public function resolveProvider($provider)
    {
        if (! $provider instanceof PaymentProvider) {
            $provider = PaymentProvider::find($provider);
        }

        if (is_null($provider) || (! $provider->exists)) {
            return null;
        }

        return $provider;
    }

    /**
     * Get the default providable identifier.
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Merchantable|null $merchant
     * @return string|int
     */
    public function getDefaultProvider(Merchantable $merchant = null)
    {
        if (! $merchant instanceof PaymentMerchant || is_null($provider = $merchant->providers()->wherePivot('is_default', true)->first())) {
            return parent::getDefaultProvider();
        }

        return $provider;
    }

    /**
     * Resolve the merchantable intance.
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Merchantable|string $merchant
     * @return \rkujawa\LaravelPaymentGateway\Contracts\Merchantable|null
     */
    public function resolveMerchant($merchant)
    {
        if (! $merchant instanceof PaymentMerchant) {
            $merchant = PaymentMerchant::find($merchant);
        }

        if (is_null($merchant) || (! $merchant->exists)) {
            return null;
        }

        return $merchant;
    }

    /**
     * Verify that the merchant is compatible with the provider.
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Providable
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Merchantable
     * @return bool
     */
    public function check($provider, $merchant)
    {
        if (! $merchant->providers->contains($provider)) {
            return false;
        }

        return true;
    }

    /**
     * Resolve the gateway class.
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Providable $provider
     * @return string
     */
    public function resolveGatewayClass($provider)
    {
        return $provider->request_class;
    }
}
