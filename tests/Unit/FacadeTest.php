<?php

namespace rkujawa\LaravelPaymentGateway\Tests\Unit;

use rkujawa\LaravelPaymentGateway\Facades\PaymentService;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;
use rkujawa\LaravelPaymentGateway\PaymentGatewayFactory;
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

    public function test_successful_set_merchant()
    {
        $defaultMerchant = config('payment.providers.' . PaymentService::getProvider() . '.defaults.merchant');
        $possibleMerchants = config('payment.providers.' . PaymentService::getProvider() . '.merchants');
        unset($possibleMerchants[$defaultMerchant]);
        PaymentService::setMerchant(array_shift($possibleMerchants));
    }

    public function test_successful_set_provider()
    {
        $allowedProvider = PaymentGatewayFactory::allowedProviders();
        $initMerchant = PaymentService::getProvider();
        unset($allowedProvider[$initMerchant]);
        $newMerchant = array_shift($allowedProvider);
        PaymentService::setProvider($newMerchant);
        $this->assertNotEquals($initMerchant, PaymentService::getMerchant());
    }

    public function test_fail_set_merchant()
    {
        $this->expectException(\Exception::class);
        $allowedProvider = PaymentGatewayFactory::allowedProviders();
        $initMerchant = PaymentService::getProvider();
        unset($allowedProvider[$initMerchant]);
        $newMerchant = array_shift($allowedProvider);
        PaymentService::setMerchant($newMerchant);
    }

    public function test_create_payment_customer()
    {
        $response = PaymentService::createPaymentCustomer($this->buyer);
        $this->assertTrue($response->isSuccessful());
        \Log::debug($response);
    }

    public function test_create_payment_method()
    {

    }

    public function test_get_payment_method()
    {

    }

    public function test_get_customer_profile()
    {

    }

    public function test_delete_customer_profile()
    {

    }

    public function test_delete_payment_method()
    {

    }

    public function test_charge_method()
    {

    }

    public function test_update_customer_profile()
    {

    }
}
