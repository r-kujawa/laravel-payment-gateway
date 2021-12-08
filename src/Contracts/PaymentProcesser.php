<?php

namespace rkujawa\LaravelPaymentGateway\Contracts;

interface PaymentProcesser
{
    /**
     * @param array|mixed $data
     * @param int $amount
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentProcessorResponse
     */
    public function charge($data, $amount);

    /**
     * TODO: Define the transaction tables and models.
     * 
     * @param mixed $paymentTransaction
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentProcessorResponse
     */
    public function void($paymentTransaction);

    /**
     * TODO: Define the transaction tables and models.
     * 
     * @param mixed $paymentTransaction
     * @param int $amount
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentProcessorResponse
     */
    public function refund($paymentTransaction, $amount);
}
