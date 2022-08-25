<?php

namespace rkujawa\LaravelPaymentGateway\Models\Traits;

trait PaymentTransactionRequests
{
    use ConfiguresPaymentGateway;

    /**
     * Fetch the transaction details from the provider.
     *
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function fetch()
    {
        return $this->gateway->getTransaction($this);
    }

    /**
     * Request the provider to void the transaction.
     *
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function void($data = [])
    {
        return $this->gateway->void($this, $data);
    }

    /**
     * Request the provider to refund the transaction.
     *
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function refund($data = [])
    {
        return $this->gateway->refund($this, $data);
    }
}
