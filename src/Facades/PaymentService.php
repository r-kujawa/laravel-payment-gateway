<?php

namespace rkujawa\LaravelPaymentGateway\Facades;

use Illuminate\Support\Facades\Facade;
use rkujawa\LaravelPaymentGateway\PaymentGatewayService;

/**
 * @method static getProvider()
 * @method static getProviderId()
 * @method static getMerchant()
 * @method static setProvider(mixed $newMerchant)
 * @method static setMerchant(mixed $newMerchant)
 * @method static createPaymentCustomer(\rkujawa\LaravelPaymentGateway\Tests\BuyerHelper $buyer)
 */
class PaymentService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PaymentService';
    }
}
