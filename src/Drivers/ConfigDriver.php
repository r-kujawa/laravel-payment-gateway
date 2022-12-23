<?php

namespace rkujawa\LaravelPaymentGateway\Drivers;

use rkujawa\LaravelPaymentGateway\Contracts\Merchantable;
use rkujawa\LaravelPaymentGateway\DataTransferObjects\Merchant;
use rkujawa\LaravelPaymentGateway\DataTransferObjects\Provider;
use rkujawa\LaravelPaymentGateway\PaymentServiceDriver;

class ConfigDriver extends PaymentServiceDriver
{
    /**
     * Collection of the application'n payment providers.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $providers;

    /**
     * Collection of the application's merchants.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $merchants;


    /**
     * Collect the application's payment providers & merchants.
     */
    public function __construct()
    {
        $this->providers = collect(config('payment.providers'));
        $this->merchants = collect(config('payment.merchants'));
    }

    /**
     * Resolve the providable instance.
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Providable|string $provider
     * @return \rkujawa\LaravelPaymentGateway\Contracts\Providable|null
     */
    public function resolveProvider($provider)
    {
        if ($provider instanceof Provider) {
            return $provider;
        }

        if (is_null($attributes = $this->providers->get($provider))) {
            return null;
        }

        return new Provider(array_merge(['id' => $provider], $attributes));
    }

    /**
     * Get the default providable identifier.
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Merchantable|null $merchant
     * @return string|int
     */
    public function getDefaultProvider(Merchantable $merchant = null)
    {
        if (
            ! $merchant instanceof Merchant ||
            is_null($provider = $merchant->providers->search(function ($provider) { return $provider['is_default'] ?? false; }))
        ) {
            return parent::getDefaultProvider();
        }

        return config('payment.providers.' . $provider . '.id');
    }

    /**
     * Resolve the merchantable intance.
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Merchantable|string $merchant
     * @return \rkujawa\LaravelPaymentGateway\Contracts\Merchantable|null
     */
    public function resolveMerchant($merchant)
    {
        if ($merchant instanceof Merchant) {
            return $merchant;
        }

        if (is_null($attributes = $this->merchants->get($merchant))) {
            return null;
        }

        return new Merchant(array_merge(['id' => $merchant], $attributes));
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
        return $merchant->providers->contains('id', $provider->id);
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
