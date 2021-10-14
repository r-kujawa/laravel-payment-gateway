<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces\Response;

interface PaymentManagementContract
{
    /**
     * @return string
     */
    public function getWalletToken();

    /**
     * @return string
     */
    public function getPaymentMethodToken();
}
