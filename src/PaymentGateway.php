<?php

namespace rkujawa\LaravelPaymentGateway;

use Illuminate\Support\Facades\App;
use rkujawa\LaravelPaymentGateway\Traits\SimulateAttributes;

use function PHPUnit\Framework\isNull;

abstract class PaymentGateway
{
    use SimulateAttributes;

    /**
     * The http client.
     *
     * @var mixed
     */
    protected $client;

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

        $this->setClient();
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
     * Set the http client for future requests.
     *
     * @return void
     */
    protected function setClient()
    {
        $this->client = $this->clientConfig($this->clientArgs());
    }

    /**
     * Defines the provider specific method to use to prepare the request.
     *
     * @param array|mixed $args
     * @return mixed Provider specific request.
     */
    abstract protected function clientConfig($args);

    /**
     * Defines the default arguments to be passed into the merchantRequest function based on the 'payments' config file.
     *
     * @return array All necesary merchant specific arguments to build the request.
     */
    protected function clientArgs()
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
            $this->setProvider();
        }

        return $this->config['provider'];
    }

    /**
     * Set the specified provider.
     *
     * @param string|null $provider
     * @return void
     */
    protected function setProvider($provider = null)
    {
        if (is_null($provider)) {
            $provider = strtolower(
                str_replace(
                    'PaymentGateway',
                    '',
                    (new \ReflectionClass($this))->getShortName()
                )
            );
        }

        $this->config['provider'] = $provider;
    }

    /**
     * Get the current merchant.
     *
     * @return string
     */
    protected function getMerchant()
    {
        if (! isset($this->config['merchant'])) {
            $this->setMerchant();
        }

        return $this->config['merchant'];
    }

    /**
     * Set the specified merchant.
     *
     * @param string|null $merchant
     * @return void
     */
    protected function setMerchant($merchant = null)
    {
        if (is_null($merchant)) {
            $merchant = config('payment.providers.' . $this->provider . '.defaults.merchant');
        }

        if (! $this->verifyMerchant($merchant)) {
            throw new \Exception('Unsupported merchant; ' . $merchant . ' for ' . $this->provider . '.');
        }

        $resetRequest = isset($this->config['merchant']);

        $this->config['merchant'] = $merchant;

        if ($resetRequest) {
            $this->setClient();
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