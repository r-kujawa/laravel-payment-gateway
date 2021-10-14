<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces\Response;

interface PaymentGatewayContract
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
