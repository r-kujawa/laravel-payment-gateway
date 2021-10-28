<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

interface PaymentProcessing
{
    /**
     * @param array|mixed $data
     * @param int $amount
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\Response\PaymentProcessorResponse
     */
    public function charge($data, $amount);

    /**
     * TODO: Define the transaction tables and models.
     * 
     * @param mixed $paymentTransaction
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\Response\PaymentProcessorResponse
     */
    public function void($paymentTransaction);

    /**
     * TODO: Define the transaction tables and models.
     * 
     * @param mixed $paymentTransaction
     * @param int $amount
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\Response\PaymentProcessorResponse
     */
    public function refund($paymentTransaction, $amount);
}
