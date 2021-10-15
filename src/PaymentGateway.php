<?php

namespace rkujawa\LaravelPaymentGateway;

use Illuminate\Support\Facades\App;
use rkujawa\LaravelPaymentGateway\Traits\SimulateAttributes;

abstract class PaymentGateway
{
    use SimulateAttributes;

    /**
     * The http request client.
     *
     * @var mixed
     */
    protected $request;

    /**
     * The payment gateway configuration variables.
     *
     * @var array
     */
    protected $config = [];

    public function __construct()
    {
        if ($this->isTesting()) {
            //TODO: Define the testing ecosystem (Fake the http client).

            return;
        }

        $this->request = $this->setRequest();
    }

    /**
     * Determines whether or not the current environmet is set to testing.
     *
     * @return boolean
     */
    protected function isTesting()
    {
        return App::environment('testing');
    }

    /**
     * Excecutes the provider specific merchantRequest function by injecting the arguments of the current provider.
     *
     * @return mixed Provider specific request.
     */
    protected function setRequest()
    {
        return $this->merchantRequest($this->merchantArgs());
    }

    /**
     * Defines the provider specific method to use to prepare the request.
     *
     * @param array|mixed $args
     * @return mixed Provider specific request.
     */
    abstract protected function merchantRequest($args);

    /**
     * Defines the default arguments to be passed into the merchantRequest function based on the 'payments' config file.
     *
     * @return array All necesary merchant specific arguments to build the request.
     */
    protected function merchantArgs()
    {
        return config('payment.providers.' . $this->provider . '.merchants.' . $this->merchant);
    }

    /**
     * Get the current provider's slug.
     *
     * @return string
     */
    protected function getProvider()
    {
        if (! isset($this->config['provider'])) {
            return strtolower(
                str_replace(
                    'PaymentGateway',
                    '',
                    (new \ReflectionClass($this))->getShortName()
                )
            );
        }

        return $this->config['provider'];
    }

    /**
     * Get the current merchant.
     *
     * @return string
     */
    protected function getMerchant()
    {
        if (! isset($this->config['merchant'])) {
            $this->setMerchant(config('payment.providers.' . $this->provider . '.defaults.merchant'));
        }

        return $this->config['merchant'];
    }

    /**
     * Set the specified merchant.
     *
     * @param string $merchant
     * @return void
     */
    protected function setMerchant($merchant)
    {
        if (! $this->verifyMerchant($merchant)) {
            throw new \Exception('Unsupported merchant; ' . $merchant . ' for ' . $this->provider . '.');
        }

        if (! isset($this->config['merchant']) || $this->config['merchant'] !== $merchant) {
            $this->config['merchant'] = $merchant;

            $this->setRequest();
        }
    }

    /**
     * Verify if the current provider supports the specified merchant.
     *
     * @param string $merchant
     * @return boolean
     */
    protected function verifyMerchant($merchant)
    {
        return in_array($merchant, $this->supportedMerchants());
    }

    /**
     * Get the supported merchants for the current provider.
     *
     * @return array
     */
    protected function supportedMerchants()
    {
        return array_keys(config('payment.providers.' . $this->provider . '.merchants'));
    }
}