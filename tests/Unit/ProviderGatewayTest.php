<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use rkujawa\LaravelPaymentGateway\Facades\Payment;
use rkujawa\LaravelPaymentGateway\Models\PaymentMethod;
use rkujawa\LaravelPaymentGateway\Models\PaymentTransaction;
use rkujawa\LaravelPaymentGateway\Models\Wallet;
use rkujawa\LaravelPaymentGateway\PaymentResponse;

class ProviderGatewayTest extends GatewayTestCase
{
    /** @test */
    public function get_wallet_method_returns_configured_response()
    {
        $wallet = Wallet::factory()->create([
            'provider_id' => $this->provider->id,
            'merchant_id' => $this->merchant->id,
        ]);

        $response = Payment::getWallet($wallet);

        $this->assertResponseIsConfigured($response);

        $this->assertEquals('getWallet', $response->data['requestMethod']);
    }

    /** @test */
    public function get_payment_method_method_returns_configured_response()
    {
        $wallet = Wallet::factory()->create([
            'provider_id' => $this->provider->id,
            'merchant_id' => $this->merchant->id,
        ]);

        $paymentMethod = PaymentMethod::factory()->create([
            'wallet_id' => $wallet->id,
        ]);

        $response = Payment::getPaymentMethod($paymentMethod);

        $this->assertResponseIsConfigured($response);

        $this->assertEquals('getPaymentMethod', $response->data['requestMethod']);
    }

    /** @test */
    public function tokenize_payment_method_method_returns_configured_response()
    {
        $user = User::factory()->create();

        $response = Payment::tokenizePaymentMethod($user, []);

        $this->assertResponseIsConfigured($response);

        $this->assertEquals('tokenizePaymentMethod', $response->data['requestMethod']);
    }

    /** @test */
    public function update_payment_method_method_returns_configured_response()
    {
        $wallet = Wallet::factory()->create([
            'provider_id' => $this->provider->id,
            'merchant_id' => $this->merchant->id,
        ]);

        $paymentMethod = PaymentMethod::factory()->create([
            'wallet_id' => $wallet->id,
        ]);

        $response = Payment::updatePaymentMethod($paymentMethod, []);

        $this->assertResponseIsConfigured($response);

        $this->assertEquals('updatePaymentMethod', $response->data['requestMethod']);
    }

    /** @test */
    public function remove_payment_method_method_returns_configured_response()
    {
        $wallet = Wallet::factory()->create([
            'provider_id' => $this->provider->id,
            'merchant_id' => $this->merchant->id,
        ]);

        $paymentMethod = PaymentMethod::factory()->create([
            'wallet_id' => $wallet->id,
        ]);

        $response = Payment::removePaymentMethod($paymentMethod);

        $this->assertResponseIsConfigured($response);

        $this->assertEquals('removePaymentMethod', $response->data['requestMethod']);
    }

    /** @test */
    public function authorize_method_returns_configured_response()
    {
        $response = Payment::authorize([]);

        $this->assertResponseIsConfigured($response);

        $this->assertEquals('authorize', $response->data['requestMethod']);
    }

    /** @test */
    public function capture_method_returns_configured_response()
    {
        $transaction = PaymentTransaction::factory()->create([
            'provider_id' => $this->provider->id,
            'merchant_id' => $this->merchant->id,
        ]);

        $response = Payment::capture($transaction);

        $this->assertResponseIsConfigured($response);

        $this->assertEquals('capture', $response->data['requestMethod']);
    }

    /** @test */
    public function authorize_and_capture_method_returns_configured_response()
    {
        $response = Payment::authorizeAndCapture([]);

        $this->assertResponseIsConfigured($response);

        $this->assertEquals('authorizeAndCapture', $response->data['requestMethod']);
    }

    /** @test */
    public function void_method_returns_configured_response()
    {
        $transaction = PaymentTransaction::factory()->create([
            'provider_id' => $this->provider->id,
            'merchant_id' => $this->merchant->id,
        ]);

        $response = Payment::void($transaction);

        $this->assertResponseIsConfigured($response);

        $this->assertEquals('void', $response->data['requestMethod']);
    }

    /** @test */
    public function refund_method_returns_configured_response()
    {
        $transaction = PaymentTransaction::factory()->create([
            'provider_id' => $this->provider->id,
            'merchant_id' => $this->merchant->id,
        ]);

        $response = Payment::refund($transaction);

        $this->assertResponseIsConfigured($response);

        $this->assertEquals('refund', $response->data['requestMethod']);
    }

    /**
     * Assert the response is configured automatically.
     *
     * @param string $requestMethod
     * @param \rkujawa\LaravelPaymentGateway\PaymentResponse $response
     * @return void
     */
    protected function assertResponseIsConfigured(PaymentResponse $response)
    {
        $this->assertEquals(Payment::getProvider()->id, $response->provider->id);
        $this->assertEquals(Payment::getMerchant()->id, $response->merchant->id);
    }
}
