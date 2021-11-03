<?php

namespace rkujawa\LaravelPaymentGateway;

use Exception;
use Illuminate\Support\Str;
use rkujawa\LaravelPaymentGateway\Traits\SimulateAttributes;

class PaymentService
{
    use SimulateAttributes;

    const MANAGER = 'manager';
    const PROCESSOR = 'processor';

    private $provider;
    private $merchant;

    private $gateway = [];

    /**
     * Fluent provider setter.
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

        $this->gateway = [];
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
     * Fluent merchant setter.
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

        $this->gateway = [];
    }

    /**
     * Get the dafault merchant.
     *
     * @return string
     */
    public function getDefaultMerchant()
    {
        return config('payment.providers.' . $this->getProvider() . '.merchants')[0];
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
            config('payment.providers.' . $this->getProvider() . '.merchants')
        );
    }

    protected function getManager()
    {
        $this->ensureServiceIsAvailable(self::MANAGER);

        if (! isset($this->gateway['manager'])) {
            $this->gateway['manager'] = $this->makeManager();
        }

        return $this->gateway['manager'];
    }

    private function makeManager()
    {
        $manager = config('payment.providers.' . $this->getProvider() . '.class');

        if (is_array($manager)) {
            $manager = $manager['manager'] ?? null;
        }

        if(is_null($manager)) {
            $manager = '\\App\\Services\\Payment\\' . Str::studly($this->getProvider()) . 'PaymentManager';
        }

        return new $manager($this->getMerchant());
    }

    protected function getProcessor()
    {
        $this->ensureServiceIsAvailable(self::PROCESSOR);

        if (! isset($this->gateway['processor'])) {
            $this->gateway['processor'] = $this->makeProcessor();
        }

        return $this->gateway['processor'];
    }

    private function makeProcessor()
    {
        $processor = config('payment.providers.' . $this->getProvider() . '.class');

        if (is_array($processor)) {
            $processor = $processor['processor'] ?? null;
        }

        if (is_null($processor)) {
            $processor = '\\App\\Services\\Payment\\' . Str::studly($this->getProvider()) . 'PaymentProcessor';
        }

        return new $processor($this->getMerchant());
    }

    private function ensureServiceIsAvailable($service)
    {
        if (isset($this->service) && $this->service !== $service) {
            throw new Exception('The ' . $this->service . ' service does not support this action.');
        }
    }
}
