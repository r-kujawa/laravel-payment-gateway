<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces\Response;

interface PaymentManagementContract extends PaymentGatewayContract
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
