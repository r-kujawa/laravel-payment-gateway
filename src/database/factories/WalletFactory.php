<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
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

        return [
            'provider_id' => $merchant->provider->id,
            'merchant_id' => $merchant->id,
            'token' => $this->faker->uuid(),
        ];
    }
}
