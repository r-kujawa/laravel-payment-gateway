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
 * @method static \rkujawa\LaravelPaymentGateway\Contracts\GatewayResponse createPaymentMethod(string $token, \rkujawa\LaravelPaymentGateway\Types\CardPayment $cardPaymentType)
 * @method static \rkujawa\LaravelPaymentGateway\Contracts\GatewayResponse deleteCustomerProfile(string $token)
 * @method static \rkujawa\LaravelPaymentGateway\Contracts\GatewayResponse updatePaymentMethod(string $customerToken, string $token, \rkujawa\LaravelPaymentGateway\Types\CardPayment $cardPaymentType)
 * @method static \rkujawa\LaravelPaymentGateway\Contracts\GatewayResponse deletePaymentMethod(string $customerToken, string $token)
 * @method static \rkujawa\LaravelPaymentGateway\Contracts\GatewayResponse updateCustomerProfile(string $getId, string $token, string $getEmail, string $description)
 */
class PaymentService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PaymentService';
    }
}
