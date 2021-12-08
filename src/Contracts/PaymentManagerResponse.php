<?php

namespace rkujawa\LaravelPaymentGateway\Contracts;

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
