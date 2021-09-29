<?php

namespace rkujawa\LaravelPaymentGateway\Facades;

use Illuminate\Support\Facades\Facade;
use rkujawa\LaravelPaymentGateway\PaymentGatewayService;

/**
 * @method static getProvider()
 * @method static getProviderId()
 * @method static getMerchant()
 */
class PaymentService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PaymentService';
    }
}
