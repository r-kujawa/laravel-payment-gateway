<?php

namespace rkujawa\LaravelPaymentGateway\Facades;

use Illuminate\Support\Facades\Facade;
use rkujawa\LaravelPaymentGateway\PaymentGateway;

/**
 * @method static \rkujawa\LaravelPaymentGateway\PaymentGateway provider($provider)
 * @method static \rkujawa\LaravelPaymentGateway\Models\PaymentProvider getProvider()
 * @method static void setProvider($provider)
 * @method static string|int|\rkujawa\LaravelPaymentGateway\Models\PaymentProvider getDefaultProvider()
 * @method static \rkujawa\LaravelPaymentGateway\PaymentGateway merchant($merchant)
 * @method static \rkujawa\LaravelPaymentGateway\Models\PaymentMerchant getMerchant()
 * @method static void setMerchant($merchant, $strict = true)
 * @method static string|int getDefaultMerchant()
 * @method static \rkujawa\LaravelPaymentGateway\PaymentResponse getWallet(\rkujawa\LaravelPaymentGateway\Models\Wallet $wallet)
 * @method static \rkujawa\LaravelPaymentGateway\PaymentResponse getPaymentMethod(\rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod)
 * @method static \rkujawa\LaravelPaymentGateway\PaymentResponse tokenizePaymentMethod(\rkujawa\LaravelPaymentGateway\Contracts\Billable $billable, $data)
 * @method static \rkujawa\LaravelPaymentGateway\PaymentResponse updatePaymentMethod(\rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod, $data)
 * @method static \rkujawa\LaravelPaymentGateway\PaymentResponse removePaymentMethod(\rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod)
 * @method static \rkujawa\LaravelPaymentGateway\PaymentResponse authorize($data, \rkujawa\LaravelPaymentGateway\Contracts\Billable $billable = null)
 * @method static \rkujawa\LaravelPaymentGateway\PaymentResponse capture(\rkujawa\LaravelPaymentGateway\Models\PaymentTransaction $transaction, $data = [])
 * @method static \rkujawa\LaravelPaymentGateway\PaymentResponse void(\rkujawa\LaravelPaymentGateway\Models\PaymentTransaction $transaction, $data = [])
 * @method static \rkujawa\LaravelPaymentGateway\PaymentResponse refund(\rkujawa\LaravelPaymentGateway\Models\PaymentTransaction $transaction, $data = [])
 * 
 * @see \rkujawa\LaravelPaymentGateway\PaymentGateway
 */
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
