<?php

namespace rkujawa\LaravelPaymentGateway\Tests\Unit;

use rkujawa\LaravelPaymentGateway\Facades\PaymentService;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;
use rkujawa\LaravelPaymentGateway\Tests\TestCase;

class FacadeTest extends TestCase
{
    public function test_get_provider_method()
    {
        $paymentGatewayProvider = PaymentService::getProvider();
        $this->assertNotEquals('', $paymentGatewayProvider);
        $this->assertEquals(config('payment.defaults.provider'), $paymentGatewayProvider);
    }

    public function test_get_provider_id()
    {
        $paymentGatewayProviderId = PaymentService::getProviderId();
        $this->assertNotNull($paymentGatewayProviderId);
        $this->assertEquals(
            PaymentProvider::ID[config('payment.defaults.provider')],
            $paymentGatewayProviderId
        );
    }

    public function test_get_merchant_method()
    {
        $merchant = PaymentService::getMerchant();
        $this->assertNotEquals('', $merchant);
    }

    public function test_charge_method()
    {

    }
}
