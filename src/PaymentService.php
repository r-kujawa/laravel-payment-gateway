<?php

namespace rkujawa\LaravelPaymentGateway;

use Exception;
use rkujawa\LaravelPaymentGateway\Contracts\Merchantable;
use rkujawa\LaravelPaymentGateway\Contracts\Providable;
use rkujawa\LaravelPaymentGateway\Traits\SimulateAttributes;

class PaymentService
{
    use SimulateAttributes;

    /**
     * The payment service driver that will handle provider & merchant configurations.
     *
     * @var \rkujawa\LaravelPaymentGateway\PaymentServiceDriver
     */
    private $driver;

    /**
     * The payment provider requests will be forwarded to.
     *
     * @var \rkujawa\LaravelPaymentGateway\Contracts\Providable
     */
    private $provider;

    /**
     * The merchant that will be passed to the provider's gateway.
     *
     * @var \rkujawa\LaravelPaymentGateway\Contracts\Merchantable
     */
    private $merchant;

    /**
     * The gateway class where requests will be executed.
     *
     * @var \rkujawa\LaravelPaymentGateway\PaymentRequest
     */
    private $gateway;

    public function __construct()
    {
        $this->driver = new (config('payment.drivers.' . config('payment.defaults.driver', 'config')));
    }

    /**
     * Fluent provider setter.
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Providable|string|int $provider
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
     * @return \rkujawa\LaravelPaymentGateway\Contracts\Providable
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
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Providable|string|int $provider
     * @return void
     */
    public function setProvider($provider)
    {
        if (is_null($provider = $this->driver->resolveProvider($provider))) {
            throw new Exception('Invalid provider.');
        }

        $this->provider = $provider;

        $this->gateway = null;
    }

    /**
     * Get the default payment provider.
     *
     * @return string|int|\rkujawa\LaravelPaymentGateway\Contracts\Providable
     */
    public function getDefaultProvider()
    {
        return $this->driver->getDefaultProvider($this->merchant);
    }

    /**
     * Fluent merchant setter.
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Merchantable|string|int $merchant
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
     * @return \rkujawa\LaravelPaymentGateway\Contracts\Merchantable
     */
    public function getMerchant()
    {
        if (! isset($this->merchant)) {
            $this->setMerchant($this->getDefaultMerchant());
        }

        return $this->merchant;
    }

    /**
     * Set the specified merchant.
     *
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Merchantable|string|int $merchant
     * @param bool $strict Make sure the merchant that is being set is supported by the current provider.
     * @return void
     */
    public function setMerchant($merchant)
    {
        if (is_null($merchant = $this->driver->resolveMerchant($merchant))) {
            throw new Exception('Invalid merchant.');
        }

        $this->merchant = $merchant;

        $this->gateway = null;
    }

    /**
     * Get the default merchant.
     *
     * @return string|int|\rkujawa\LaravelPaymentGateway\Contracts\Merchantable
     */
    public function getDefaultMerchant()
    {
        return $this->driver->getDefaultMerchant($this->provider);
    }

    /**
     * Get the payment gateway service.
     *
     * @return \rkujawa\LaravelPaymentGateway\PaymentRequest
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
        $provider = $this->getProvider();
        $merchant = $this->getMerchant();

        if (! $this->driver->check($merchant, $provider)) {
            throw new Exception('The ' . $provider->getName() . ' provider does not support the ' . $merchant->getName() . ' merchant.');
        }

        $gateway = config('payment.test_mode', false)
            ? config('payment.test.gateway', '\\App\\Services\\Payment\\TestPaymentGateway')
            : $this->driver->resolveGatewayClass($provider);

        if (! class_exists($gateway)) {
            throw new Exception('The ' . $gateway . '::class does not exist.');
        }

        // TODO [3.x]: $param[0] should be provider & $param[1] should be merchant.
        $this->gateway = new $gateway($merchant, $provider);
    }
}
