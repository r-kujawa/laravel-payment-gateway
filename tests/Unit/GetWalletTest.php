<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use Illuminate\Support\Str;
use rkujawa\LaravelPaymentGateway\Facades\Payment;
use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;
use rkujawa\LaravelPaymentGateway\Models\Wallet;
use rkujawa\LaravelPaymentGateway\PaymentRequest;
use rkujawa\LaravelPaymentGateway\PaymentResponse;
use rkujawa\LaravelPaymentGateway\PaymentStatus;

class GetWalletTest extends TestCase
{
    protected $provider;
    protected $merchant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = PaymentProvider::create([
            'name' => 'Test',
            'slug' => 'test',
        ]);

        $this->merchant = PaymentMerchant::create([
            'name' => 'Tester',
            'slug' => 'tester',
            'display_name' => 'Tester',
        ]);

        $this->merchant->providers()->attach($this->provider->id, ['is_default' => true]);

        config([
            'payment.defaults.provider' => $this->provider->slug,
            'payment.defaults.merchant' => $this->merchant->slug,
            'payment.providers.' . $this->provider->slug . '.path' => TestPaymentGateway::class,
        ]);
    }

    /** @test */
    public function get_wallet_method_returns_valid_information_about_it()
    {
        $wallet = Wallet::factory()->create(['provider_id' => $this->provider->id]);

        $response = Payment::getWallet($wallet);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($wallet->token, $response->data->token);
    }
}

class TestPaymentGateway extends PaymentRequest
{
    public function getWallet(Wallet $wallet)
    {
        return new TestPaymentResponse(json_encode([
            'code' => PaymentStatus::AUTHORIZED,
            'reference' => Str::uuid(),
            'token' => $wallet->token,
            'paymentMethods' => $wallet->paymentMethods->map(function ($paymentMethod) {
                return [
                    'token' => $paymentMethod->token,
                    'first_name' => $paymentMethod->first_name,
                    'last_name' => $paymentMethod->last_name,
                    'lastDigits' => $paymentMethod->last_digits,
                    'exp_month' => $paymentMethod->exp_month,
                    'exp_year' => $paymentMethod->exp_year,
                    'type' => $paymentMethod->type_id,
                ];
            })->toArray(),
        ]));
    }
}

class TestPaymentResponse extends PaymentResponse
{
    public function walletDetails()
    {
        return json_decode($this->raw);
    }

    public function getStatusCode()
    {
        return $this->data->code;
    }
}
