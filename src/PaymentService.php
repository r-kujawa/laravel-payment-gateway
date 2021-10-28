<?php

namespace rkujawa\LaravelPaymentGateway;

use Exception;
use rkujawa\LaravelPaymentGateway\Traits\SimulateAttributes;

class PaymentService
{
    use SimulateAttributes;

    const FULL = 'full';
    const PROCESSOR = 'processor';
    const MANAGER = 'manager';

    protected $service = 'full';

    private $provider;
    private $merchant;
    private $gateway;

    /**
     * Get or set the payment provider.
     *
     * @param string $provider
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
     * @return string
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
     * @param string $provider
     * @return void
     * 
     * @throws \Exception
     */
    public function setProvider($provider)
    {
        if (! $this->isValidProvider($provider)) {
            throw new Exception('Unsupported provider; ' . $provider . '.');
        }

        $this->provider = $provider;

        $this->gateway = null;
        $this->merchant = null;
    }

    /**
     * Get the default payment provider.
     *
     * @return string
     */
    public function getDefaultProvider()
    {
        return config('payment.defaults.provider');
    }

    /**
     * Verify if the payment provider is supported.
     *
     * @param string $provider
     * @return boolean
     */
    private function isValidProvider($provider)
    {
        return in_array(
            $provider,
            array_keys(config('payment.providers'))
        );
    }

    /**
     * Get or set the merchant.
     *
     * @param string $merchant
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
     * @return string
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
     * @param string $merchant
     * @return void
     */
    public function setMerchant($merchant)
    {
        if (! $this->isValidMerchant($merchant)) {
            throw new Exception('Unsupported merchant; ' . $merchant . ' for ' . $this->getProvider() . '.');
        }

        $this->merchant = $merchant;

        $this->gateway = null;
    }

    /**
     * Get the dafault merchant.
     *
     * @return string
     */
    public function getDefaultMerchant()
    {
        return config('payment.providers.' . $this->getProvider() . '.defaults.merchant');
    }

    /**
     * Verify if the current provider supports the specified merchant.
     *
     * @param string $merchant
     * @return boolean
     */
    private function isValidMerchant($merchant)
    {
        return in_array(
            $merchant,
            array_keys(config('payment.providers.' . $this->getProvider() . '.merchants'))
        );
    }

    protected function getGateway()
    {
        if (! isset($this->gateway)) {
            $this->prepareGateway();
        }

        return $this->gateway;
    }

    /**
     * Set the payment gateway.
     *
     * @return void
     */
    private function prepareGateway()
    {
        $gateway = config('payment.providers.' . $this->getProvider() . '.class');

        if(is_array($gateway)) {
            $gateway = $gateway[$this->service];
        }

        $config = config('payment.providers.' . $this->getProvider() . '.merchants.' . $this->getMerchant());

        $this->gateway = new $gateway($config);
    }
}
