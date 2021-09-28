<?php

namespace rkujawa\LaravelPaymentGateway\Facades;

use Illuminate\Support\Facades\Facade;
use rkujawa\LaravelPaymentGateway\PaymentGatewayService;

class PaymentService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PaymentGatewayService::class;
    }
}
