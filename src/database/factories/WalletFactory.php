<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;
use rkujawa\LaravelPaymentGateway\Models\Wallet;

class WalletFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wallet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $merchant = PaymentMerchant::inRandomOrder()->firstOr(function () {
            return PaymentMerchant::factory()->create();
        });

        $provider = PaymentProvider::whereHas('merchants', function ($query) use ($merchant) {
            $query->where('payment_merchants.id', $merchant->id);
        })->inRandomOrder()->firstOr(function () use ($merchant) {
            $paymentProvider = PaymentProvider::factory()->create();

            $merchant->providers()->attach($paymentProvider->id, ['is_default' => true]);

            return $paymentProvider;
        });

        return [
            'provider_id' => $provider->id,
            'merchant_id' => $merchant->id,
            'token' => $this->faker->uuid(),
        ];
    }
}
