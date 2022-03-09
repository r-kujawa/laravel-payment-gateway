<?php

namespace rkujawa\LaravelPaymentGateway\Models\Traits;

trait PaymentMethodRequests
{
    use ConfiguresPaymentGateway;

    /**
     * Fetch the payment method details from the provider.
     *
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function fetch()
    {
        return $this->gateway->getPaymentMethod($this);
    }

    /**
     * Request the provider to update the payment method's details.
     *
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function patch($data)
    {
        return $this->gateway->updatePaymentMethod($this, $data);
    }

    /**
     * Request the provider to remove the payment method from their system.
     *
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function disable()
    {
        return $this->gateway->deletePaymentMethod($this);
    }
}
