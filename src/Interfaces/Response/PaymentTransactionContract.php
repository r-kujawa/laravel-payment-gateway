<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces\Response;

interface PaymentTransactionContract
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
