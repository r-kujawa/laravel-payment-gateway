<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces\Response;

interface PaymentManagementContract
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
