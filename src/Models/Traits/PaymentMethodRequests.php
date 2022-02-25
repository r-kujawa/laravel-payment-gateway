<?php

namespace rkujawa\LaravelPaymentGateway\Models\Traits;

trait PaymentMethodRequests
{
    use ConfiguresPaymentGateway;

    /**
     * Request the payment method details from the provider.
     *
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentResponse
     */
    public function requestDetails()
    {
        return $this->gateway->getPaymentMethod($this);
    }

    /**
     * Request the provider to update the payment method's details.
     *
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentResponse
     */
    public function requestUpdate($data)
    {
        return $this->gateway->updatePaymentMethod($this, $data);
    }

    /**
     * Request the provider to remove the payment method from their system.
     *
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentResponse
     */
    public function requestRemoval()
    {
        return $this->gateway->removePaymentMethod($this);
    }

    /**
     * Request authorization for a payment.
     *
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentResponse
     */
    public function authorize($data)
    {
        return $this->gateway->authorize($data, $this);
    }

    /**
     * Request authorization for a payment and capture it.
     *
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentResponse
     */
    public function authorizeAndCapture($data)
    {
        return $this->gateway->authorizeAndCapture($data, $this);
    }
}
