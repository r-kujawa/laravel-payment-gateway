<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

interface PaymentResponse
{
    /**
     * @return bool
     */
    public function isSuccessful();

    /**
     * @return string
     */
    public function raw();

    /**
     * @return string
     */
    public function code();
}
