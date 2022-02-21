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
        $this->merchant = null;
    }

    /**
     * Get the default payment provider.
     *
     * @return string
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
            throw new Exception('Unsupported provider.');
        }

        return $provider;
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
            $this->setMerchant($this->getDefaultMerchant());
        }

        return $this->merchant;
    }

    /**
     * Set the specified merchant.
     *
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMerchant|string|int $merchant
     * @return void
     */
    public function setMerchant($merchant)
    {
        $this->merchant = $this->ensureMerchantIsValid($merchant);

        $this->gateway = [];
    }

    /**
     * Get the dafault merchant.
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

        if (is_null($merchant) || (! $merchant->exists) || (! $merchant->providers()->where('slug', $this->getProvider()->slug)->exists())) {
            throw new Exception('Unsupported merchant.');
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

        return new $gateway($this->getMerchant());
    }
}
