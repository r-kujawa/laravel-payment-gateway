<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces\Response;

interface PaymentTransactionContract extends PaymentGatewayContract
{
    /**
     * @return string
     */
    public function transactionId();

    /**
     * @return float
     */
    public function transactionAmount();

    /**
     * @return string
     */
    public function avsCode();
}
