<?php

namespace rkujawa\LaravelPaymentGateway\Contracts;

interface PaymentServiceResponse
{
    /**
     * @return bool
     */
    public function isSuccessful();

    /**
     * @return array
     */
    public function raw();

    /**
     * @return string
     */
    public function code();
}
