<?php

namespace rkujawa\LaravelPaymentGateway\Facades;

use Illuminate\Support\Facades\Facade;
use rkujawa\LaravelPaymentGateway\PaymentGateway;

class Payment extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PaymentGateway::class;
    }
}
