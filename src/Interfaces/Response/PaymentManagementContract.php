<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces\Response;

interface PaymentManagementContract
{
    public function getWalletToken();
    public function getPaymentMethodToken();
}