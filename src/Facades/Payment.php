<?php

namespace rkujawa\LaravelPaymentGateway\Facades;

use Illuminate\Support\Facades\Facade;
use rkujawa\LaravelPaymentGateway\PaymentGateway;

class Payment extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PaymentGateway::class;
    }
}
