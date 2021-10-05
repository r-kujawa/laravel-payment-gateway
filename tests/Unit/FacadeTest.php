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

    public function test_crud_payment_customer()
    {
        //create
        $responseCreate = PaymentService::createPaymentCustomer($this->buyer);
        $this->assertTrue($responseCreate->isSuccessful());
        $this->paymentCustomer = PaymentCustomer::findByToken($responseCreate->getCustomerProfileId());
        $this->assertNotNull($this->paymentCustomer);
        //update
        $responseUpdate = PaymentService::updateCustomerProfile(
            $this->buyer->getId(),
            $this->paymentCustomer->token,
            $this->buyer->getEmail(),
            $this->buyer->getFullName() . ' updated'
        );
        $this->assertTrue($responseUpdate->isSuccessful());
        //read
        $responseRead = PaymentService::getCustomerProfile($this->paymentCustomer->token);
        $this->assertTrue($responseRead->isSuccessful());
        $responseObj = json_decode($responseRead->getRawResponse());
        $this->assertTrue(str_contains($responseObj->profile->description, 'updated'));
        //delete
        $responseDelete = PaymentService::deleteCustomerProfile($this->paymentCustomer->token);
        $this->assertTrue($responseDelete->isSuccessful());
        $this->assertNull(PaymentCustomer::find($this->paymentCustomer->token));
        unset($this->paymentCustomer);
    }

    public function test_crud_payment_method()
    {
        [$customerToken, $_] = $this->getSandboxTokens();
        PaymentCustomer::create(
            [
                'token' => $customerToken,
                'provider_id' => PaymentProvider::ID['authorize']
            ]
        );
        //create
        $cardPaymentType = CardPaymentTypeFactory::getInstance();
        $responseCreate = PaymentService::createPaymentMethod($customerToken, $cardPaymentType);
        $this->assertTrue($responseCreate->isSuccessful());

        $paymentMethodModel = PaymentMethod::findByToken($responseCreate->getPaymentProfileId());
        $this->assertNotNull($responseCreate);
        $this->assertNotNull($paymentMethodModel);
        //update
        $cardPaymentType->address->city .= ' Modified';
        $responseUpdate = PaymentService::updatePaymentMethod($customerToken, $paymentMethodModel->token, $cardPaymentType);
        $this->assertTrue($responseUpdate->isSuccessful());
        //read
        $responseGet = PaymentService::getPaymentMethod($customerToken, $paymentMethodModel->token);
        $this->assertTrue($responseGet->isSuccessful());
        $updatedPaymentMethod = json_decode($responseGet->getRawResponse());
        $this->assertTrue(str_contains($updatedPaymentMethod->paymentProfile->billTo->city, 'Modified'));
        //delete
        $responseDelete = PaymentService::deletePaymentMethod($customerToken, $paymentMethodModel->token);
        $this->assertTrue($responseDelete->isSuccessful());
        $this->assertNull(PaymentMethod::findByToken($paymentMethodModel->token));
    }

    public function test_charge_non_existing_payment_method()
    {

    }

    public function test_charge_existing_payment_method()
    {

    }

    private function getSandboxTokens(): array
    {
        /*Those reached 10 payment profiles, can be used to test that exception
         * 'paymentCustomer' => '501833167',
        'paymentMethod' => '503085944'
         * */
        return [
            \config('payment.defaults.paymentCustomer'),
            \config('payment.defaults.paymentMethod')
        ];
    }

    private function checkCustomer(): void
    {
        if (! $this->paymentCustomer) {
            $customerId = PaymentService::createPaymentCustomer($this->buyer)->getCustomerProfileId();
            $this->paymentCustomer = PaymentCustomer::findByToken($customerId);
        }
    }
}
