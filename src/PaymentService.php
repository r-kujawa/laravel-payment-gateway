<?php

namespace rkujawa\LaravelPaymentGateway;

use Exception;
use Illuminate\Support\Str;
use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;
use rkujawa\LaravelPaymentGateway\Traits\SimulateAttributes;

class PaymentService
{
    use SimulateAttributes;

    /**
     * The payment provider requests will be forwarded to.
     *
     * @var \rkujawa\LaravelPaymentGateway\Models\PaymentProvider
     */
    private $provider;

    /**
     * The merchant that will be passed to the provider's gateway.
     *
     * @var \rkujawa\LaravelPaymentGateway\Models\PaymentMerchant
     */
    private $merchant;

    /**
     * The gateway class where requests will be executed.
     *
     * @var \rkujawa\LaravelPaymentGateway\Contracts\PaymentGateway
     */
    private $gateway;

    /**
     * Fluent provider setter.
     *
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentProvider|string|int $provider
     * @return \rkujawa\LaravelPaymentGateway\PaymentService
     */
    public function provider($provider)
    {
        $this->setProvider($provider);

        return $this;
    }

    /**
     * Get the current payment provider.
     *
     * @return \rkujawa\LaravelPaymentGateway\Models\PaymentProvider
     */
    public function getProvider()
    {
        if (! isset($this->provider)) {
            $this->setProvider($this->getDefaultProvider());
        }

        return $this->provider;
    }

    /**
     * Set the payment provider.
     *
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentProvider|string|int $provider
     * @return void
     */
    public function setProvider($provider)
    {
        $this->provider = $this->ensureProviderIsValid($provider);

        $this->gateway = null;
    }

    /**
     * Get the default payment provider.
     *
     * @return string|int|\rkujawa\LaravelPaymentGateway\Models\PaymentProvider
     */
    public function getDefaultProvider()
    {
        if (isset($this->merchant) && ! is_null($provider = $this->merchant->providers()->wherePivot('is_default', true)->first())) {
            return $provider;
        }

        return config('payment.defaults.provider');
    }

    /**
     * Verify if the payment provider is supported.
     *
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentProvider|string|int $provider
     * @return \rkujawa\LaravelPaymentGateway\Models\PaymentProvider
     *
     * @throws \Exception
     */
    private function ensureProviderIsValid($provider)
    {
        if (! $provider instanceof PaymentProvider) {
            $provider = PaymentProvider::where('slug', $provider)->orWhere('id', $provider)->first();
        }

        if (is_null($provider) || (! $provider->exists)) {
            throw new Exception('Provider not found.');
        }

        return $provider;
    }

    /**
     * Ensure the merchant has a relationship with the provider and return it.
     *
     * @return \rkujawa\LaravelPaymentGateway\Models\PaymentMerchant
     */
    private function ensureMerchantIsSupportedByProvider()
    {
        if (! $this->getMerchant()->providers->contains('id', $this->getProvider()->id)) {
            throw new Exception('The ' . $this->getProvider()->name . ' provider does not support the ' . $this->getMerchant()->name . ' merchant.');
        }

        return $this->getMerchant();
    }

    /**
     * Fluent merchant setter.
     *
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMerchant|string|int $merchant
     * @return \rkujawa\LaravelPaymentGateway\PaymentService
     */
    public function merchant($merchant)
    {
        $this->setMerchant($merchant);

        return $this;
    }

    /**
     * Get the current merchant.
     *
     * @return \rkujawa\LaravelPaymentGateway\Models\PaymentMerchant
     */
    public function getMerchant()
    {
        if (! isset($this->merchant)) {
            $this->setMerchant($this->getDefaultMerchant(), false);
        }

        return $this->merchant;
    }

    /**
     * Set the specified merchant.
     *
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMerchant|string|int $merchant
     * @param bool $strict Make sure the merchant that is being set is supported by the current provider.
     * @return void
     */
    public function setMerchant($merchant, $strict = true)
    {
        $this->merchant = $this->ensureMerchantIsValid($merchant);

        if ($strict) {
            $this->ensureMerchantIsSupportedByProvider();
        }

        $this->gateway = null;
    }

    /**
     * Get the default merchant.
     *
     * @return string|int
     */
    public function getDefaultMerchant()
    {
        return config('payment.defaults.merchant');
    }

    /**
     * Verify if the current provider supports the specified merchant.
     *
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMerchant|string|int $merchant
     * @return \rkujawa\LaravelPaymentGateway\Models\PaymentMerchant
     *
     * @throws \Exception
     */
    private function ensureMerchantIsValid($merchant)
    {
        if (! $merchant instanceof PaymentMerchant) {
            $merchant = PaymentMerchant::where('slug', $merchant)->orWhere('id', $merchant)->first();
        }

        if (is_null($merchant) || (! $merchant->exists)) {
            throw new Exception('Merchant not found.');
        }

        return $merchant;
    }

    /**
     * Get the payment gateway service.
     *
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentGateway
     */
    protected function getGateway()
    {
        if (! isset($this->gateway)) {
            $this->setGateway();
        }

        return $this->gateway;
    }

    /**
     * Instantiate a new instance of the payment gateway.
     *
     * @return void
     */
    protected function setGateway()
    {
        $gateway = config('payment.test_mode', false)
            ? config(
                'payment.test.gateway',
                '\\App\\Services\\Payment\\TestPaymentGateway'
            )
            : config(
                'payment.providers.' . $this->getProvider()->slug . '.gateway',
                '\\App\\Services\\Payment\\' . Str::studly($this->getProvider()->slug) . 'PaymentGateway'
            );

        $this->gateway = $this->ensureGatewayIsValid($gateway);
    }

    /**
     * Verify that the requested action is legal and the gateway exists.
     *
     * @param string $gateway
     * @return mixed
     *
     * @throws \Exception
     */
    private function ensureGatewayIsValid($gateway)
    {
        if (! class_exists($gateway)) {
            throw new Exception('The ' . $gateway . '::class does not exist.');
        }

        return new $gateway($this->ensureMerchantIsSupportedByProvider());
    }
}
