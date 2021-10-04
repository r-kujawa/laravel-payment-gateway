<?php

namespace rkujawa\LaravelPaymentGateway\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static getProvider()
 * @method static getProviderId()
 * @method static getMerchant()
 * @method static setProvider(mixed $newMerchant)
 * @method static setMerchant(mixed $newMerchant)
 * @method static \rkujawa\LaravelPaymentGateway\Contracts\GatewayResponse createPaymentCustomer(\rkujawa\LaravelPaymentGateway\Contracts\Buyer $buyer)
 * @method static \rkujawa\LaravelPaymentGateway\Contracts\GatewayResponse getPaymentMethod(string $customerToken, string $paymentToken)
 * @method static \rkujawa\LaravelPaymentGateway\Contracts\GatewayResponse getCustomerProfile(string $customerToken)
 */
class PaymentService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PaymentService';
    }
}
