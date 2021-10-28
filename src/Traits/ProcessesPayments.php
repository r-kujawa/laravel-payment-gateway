<?php

namespace rkujawa\LaravelPaymentGateway\Traits;

trait ProcessesPayments
{
    /**
     * @param array|mixed $data
     * @param int $amount
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentProcessorResponse
     */
    public function charge($data, $amount)
    {
        return $this->gateway->charge($data, $amount);
    }

    /**
     * TODO: Define the transaction tables and models.
     * 
     * @param mixed $paymentTransaction
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentProcessorResponse
     */
    public function void($paymentTransaction)
    {
        return $this->gateway->void($paymentTransaction);
    }

    /**
     * TODO: Define the transaction tables and models.
     * 
     * @param mixed $paymentTransaction
     * @param int $amount
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentProcessorResponse
     */
    public function refund($paymentTransaction, $amount)
    {
        return $this->gateway->refund($paymentTransaction, $amount);
    }
}
