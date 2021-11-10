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

    protected $service;

    private $provider;
    private $merchant;

    private $gateway = [];

    public function __construct($service = null)
    {
        $this->service = $service;
    }

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
     */
    public function setProvider($provider)
    {
        $this->ensureProviderIsValid($provider);

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
     * @return void
     * 
     * @throws \Exception
     */
    private function ensureProviderIsValid($provider)
    {
        $validProvider = in_array(
            $provider,
            array_keys(config('payment.providers'))
        );

        if (! $validProvider) {
            throw new Exception('Unsupported provider; ' . $provider . '.');
        }
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
        $this->ensureMerchantIsValid($merchant);

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
     * @return void
     * 
     * @throws \Exception
     */
    private function ensureMerchantIsValid($merchant)
    {
        $validMerchant = in_array(
            $merchant,
            config('payment.providers.' . $this->getProvider() . '.merchants')
        );

        if (! $validMerchant) {
            throw new Exception('Unsupported merchant; ' . $merchant . ' for ' . $this->getProvider() . '.');
        }
    }

    /**
     * Get the payment manager service.
     *
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentManager
     */
    protected function getManager()
    {
        if (! isset($this->gateway[self::MANAGER])) {
            $this->setService(self::MANAGER);
        }

        return $this->gateway[self::MANAGER];
    }

    /**
     * Get the payment processor service.
     *
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentProcesser
     */
    protected function getProcessor()
    {
        if (! isset($this->gateway['processor'])) {
            $this->setService(self::PROCESSOR);
        }

        return $this->gateway['processor'];
    }

    /**
     * Intantiate the specified payment service and set it.
     *
     * @return void
     */
    protected function setService($service)
    {
        $gateway = config(
            'payment.providers.' . $this->getProvider() . '.' . $service,
            '\\App\\Services\\Payment\\' . Str::studly($this->getProvider()) . 'Payment' . Str::studly($service)
        );

        $this->ensureServiceIsValid($service, $gateway);

        $this->gateway[$service] = new $gateway($this->getMerchant());
    }

    /**
     * Verify that the requested action is legal and the service exists.
     *
     * @param string $service
     * @param string $gateway
     * @return void
     * 
     * @throws \Exception
     */
    private function ensureServiceIsValid($service, $gateway)
    {
        if (isset($this->service) && $this->service !== $service) {
            throw new Exception('The ' . $this->service . ' service does not support this action.');
        }

        if (! class_exists($gateway)) {
            throw new Exception('The ' . $gateway . '::class does not exist, if you moved or renamed your ' . $service . ' service class, please specify it in the payment.php config.');
        }
    }
}
