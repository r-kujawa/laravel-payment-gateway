<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

interface PaymentProcessorResponse extends PaymentResponse
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
