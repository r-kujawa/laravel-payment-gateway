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

    const MANAGER = 'manager';
    const PROCESSOR = 'processor';

    private $provider;
    private $merchant;

    private $gateway = [];

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

        $this->gateway = [];
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

    private function ensureMerchantIsSupportedByProvider($nullable = false)
    {
        if ($this->merchant->providers->doesntContain('id', $this->getProvider()->id)) {
            return $nullable
                ? null
                : throw new Exception('The ' . $this->getProvider()->name . ' provider does not support the ' . $this->merchant->name . ' merchant.');
        }

        return $this->merchant;
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
     * @param bool $strict Make sure the merchant this is being set is supported by the current provider.
     * @return void
     */
    public function setMerchant($merchant, $strict = true)
    {
        $this->merchant = $this->ensureMerchantIsValid($merchant);

        if ($strict) {
            $this->ensureMerchantIsSupportedByProvider();
        }

        $this->gateway = [];
    }

    /**
     * Get the default merchant.
     *
     * @return string
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
     * Get the payment manager service.
     *
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentManager
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
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentProcesser
     */
    protected function getProcessor()
    {
        if (! isset($this->gateway['processor'])) {
            $this->setService(self::PROCESSOR);
        }

        return $this->gateway['processor'];
    }

    /**
     * Instantiate the specified payment service and set it.
     *
     * @return void
     */
    protected function setService($service)
    {
        $gateway = config(
            'payment.providers.' . $this->getProvider()->slug . '.' . $service,
            config(
                'payment.providers.' . $this->getProvider()->slug . '.path',
                '\\App\\Services\\Payment'
            ) . '\\' . Str::studly($this->getProvider()->slug) . 'Payment' . Str::studly($service)
        );

        $this->gateway[$service] = $this->ensureServiceIsValid($service, $gateway);
    }

    /**
     * Verify that the requested action is legal and the service exists.
     *
     * @param string $service
     * @param string $gateway
     * @return mixed
     * 
     * @throws \Exception
     */
    private function ensureServiceIsValid($service, $gateway)
    {
        if (! class_exists($gateway)) {
            throw new Exception('The ' . $gateway . '::class does not exist, if you moved or renamed your ' . $service . ' service class, please specify it in the payment.php config.');
        }

        return new $gateway($this->ensureMerchantIsSupportedByProvider(true));
    }
}
