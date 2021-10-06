<?php

namespace rkujawa\LaravelPaymentGateway;

namespace rkujawa\LaravelPaymentGateway;

use rkujawa\LaravelPaymentGateway\Contracts\GatewayResponse;
use rkujawa\LaravelPaymentGateway\Contracts\PaymentType;
use rkujawa\LaravelPaymentGateway\Models\PaymentCustomer;
use rkujawa\LaravelPaymentGateway\Models\PaymentMethod;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;
use rkujawa\LaravelPaymentGateway\Traits\MagicalAttributes;

abstract class PaymentGateway
{
    use MagicalAttributes;

    public $isFake = false;

    protected function getProvider(): string
    {
        if (!isset($this->attributes['provider'])) {
            $this->attributes['provider'] = strtolower(
                str_replace(
                    'Gateway',
                    '',
                    (new \ReflectionClass($this))->getShortName()
                )
            );
        }

        return $this->attributes['provider'];
    }

    protected function getProviderId(): int
    {
        return PaymentProvider::ID[$this->provider];
    }

    protected function getMerchant(): string
    {
        if (!isset($this->attributes['merchant'])) {
            $this->setMerchant(config('payment.providers.' . $this->provider . '.defaults.merchant'));
        }

        return $this->attributes['merchant'];
    }

    protected function setMerchant(string $merchant): void
    {
        if (!in_array($merchant, $this->allowedMerchants())) {
            throw new \Exception('Unsupported merchant; ' . $merchant . ' for ' . $this->provider . '.');
        }

        if (!isset($this->attributes['merchant']) || $this->attributes['merchant'] !== $merchant) {
            $this->attributes['merchant'] = $merchant;

            $this->client = $this->buildClient();
        }
    }

    protected function allowedMerchants(): array
    {
        return array_keys(config('payment.providers.' . $this->provider . '.merchants'));
    }

    /**
     * Defines the provider specific method to use to prepare the request.
     *
     * @param array $args
     * @return mixed Provider specific request.
     */
    abstract protected function merchantRequest(array $args);

    /**
     * Defines the default arguments to be passed into the merchantRequest function based on the 'payments' config file.
     *
     * @return array All necesary merchant specific arguments to build the request.
     */
    final protected function merchantArgs(): array
    {
        if (config('app.env') !== 'production') {
            return config('payment.providers.' . $this->provider . '.sandbox');
        }

        return config('payment.providers.' . $this->provider . '.merchants.' . $this->merchant);
    }

    /**
     * Excecutes the provider specific merchantRequest function by injecting the arguments of the current provider.
     *
     * @return mixed Provider specific request.
     */
    protected function buildClient()
    {
        return $this->merchantRequest($this->merchantArgs());
    }

    protected function getClient()
    {
        if (!isset($this->attribute['client'])) {
            $this->setClient($this->buildClient());
        }

        return $this->attributes['client'];
    }

    protected function setClient($client): void
    {
        $this->attributes['client'] = $client;
    }

    // maybe this should be abtract
    public function charge(PaymentType $payment, int $amount, string $description): GatewayResponse
    {
        $charge = 'charge' . \Str::studly($payment->getPaymentType());

        if (!method_exists($this, $charge)) {
            throw new \Exception(
                'the requested payment method is not supported by the ' . $this->provider . ' provider.'
            );
        }

        return $this->$charge($payment, $amount, $description, $order);
    }

    /**
     * Builds a unique reference id for the transaction.
     *
     * @param string $method The action to be excecuted.
     * @return string Unique reference id.
     *
     * @todo Make this a unique ID.
     */
    protected function getRefId(string $method): string
    {
        return $method . time();
    }

    protected function storePaymentCustomer(array $data): PaymentCustomer
    {
        return PaymentCustomer::create($data);
    }

    protected function storePaymentMethod(array $data): PaymentMethod
    {
        return PaymentMethod::findByToken($data['token']) ?: PaymentMethod::create($data);
    }
}
