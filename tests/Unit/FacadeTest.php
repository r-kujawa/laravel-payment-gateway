<?php

namespace rkujawa\LaravelPaymentGateway\Tests\Unit;

use rkujawa\LaravelPaymentGateway\Database\Factories\CardPaymentTypeFactory;
use rkujawa\LaravelPaymentGateway\Facades\PaymentService;
use rkujawa\LaravelPaymentGateway\Models\PaymentCustomer;
use rkujawa\LaravelPaymentGateway\Models\PaymentMethod;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;
use rkujawa\LaravelPaymentGateway\PaymentGatewayFactory;
use rkujawa\LaravelPaymentGateway\Tests\TestCase;

//should we create tests for model and verify the data is being stored as expected.
class FacadeTest extends TestCase
{
    private $paymentCustomer;

    public function test_get_provider_method()
    {
        $paymentGatewayProvider = PaymentService::getProvider();
        $this->assertNotEquals('', $paymentGatewayProvider);
        $this->assertEquals(config('payment.defaults.provider'), $paymentGatewayProvider);
    }

/*    public function test_get_provider_id()
    {
        $paymentGatewayProviderId = PaymentService::getProviderId();
        $this->assertNotNull($paymentGatewayProviderId);
        $this->assertEquals(
            PaymentProvider::ID[config('payment.defaults.provider')],
            $paymentGatewayProviderId
        );
    }*/

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
        PaymentService::setMerchant(array_key_first($possibleMerchants));
        $this->assertNotEquals($defaultMerchant, PaymentService::getMerchant());
    }

    public function test_successful_set_provider()
    {
        $allowedProvider = PaymentGatewayFactory::allowedProviders();
        $initProvider = PaymentService::getProvider();
        unset($allowedProvider[$initProvider]);
        $newProvider = array_key_first($allowedProvider);
        PaymentService::setProvider($newProvider);
        $this->assertNotEquals($initProvider, PaymentService::getProvider());
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
        $this->paymentCustomer = PaymentCustomer::findByToken($response->getCustomerProfileId());
        $this->assertNotNull($this->paymentCustomer);
    }

    public function test_create_payment_method()
    {
        if (! $this->paymentCustomer) {
             $customerId = PaymentService::createPaymentCustomer($this->buyer)->getCustomerProfileId();
             $this->paymentCustomer = PaymentCustomer::findByToken($customerId);
        }

        $cardPaymentType = CardPaymentTypeFactory::getInstance();
        $response = PaymentService::createPaymentMethod($this->paymentCustomer->token, $cardPaymentType);
        $this->assertNotNull($response);
        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull(PaymentMethod::findByToken($response->getPaymentProfileId()));
    }

    public function test_successful_get_customer_profile()
    {
        $customerToken = \config('payment.defaults.paymentCustomer');
        $response = PaymentService::getCustomerProfile($customerToken);
        $this->assertTrue($response->isSuccessful());
    }

    public function test_get_payment_method()
    {
        [$customerToken, $paymentToken] = $this->getTestingTokens();
        $response = PaymentService::getPaymentMethod($customerToken, $paymentToken);
        $this->assertTrue($response->isSuccessful());
    }

    /*public function test_delete_customer_profile()
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

    }*/

    private function getTestingTokens(): array
    {
        return [
            \config('payment.defaults.paymentCustomer'),
            \config('payment.defaults.paymentMethod')
        ];
    }
}
