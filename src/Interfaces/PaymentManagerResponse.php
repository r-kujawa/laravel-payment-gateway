<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

interface PaymentManagerResponse extends PaymentResponse
{
    /**
     * @return string
     */
    public function walletToken();

    /**
     * @return string
     */
    public function paymentMethodToken();
}