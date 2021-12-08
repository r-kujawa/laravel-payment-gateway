<?php

namespace rkujawa\LaravelPaymentGateway\Contracts;

interface PaymentProcessorResponse extends PaymentServiceResponse
{
    /**
     * @return string
     */
    public function transactionId();

    /**
     * @return int
     */
    public function transactionAmount();

    /**
     * @return string
     */
    public function avsCode();
}
