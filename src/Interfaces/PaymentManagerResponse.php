<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

interface PaymentManagerResponse extends PaymentServiceResponse
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
