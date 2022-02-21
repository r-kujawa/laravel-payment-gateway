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
        return [
            'token' => $this->faker->uuid(),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Wallet $wallet) {
            if (is_null($wallet->provider_id)) {
                $provider = PaymentProvider::whereHas('merchants', function ($query) use ($wallet) {
                    $query->where('payment_merchants.id', $wallet->merchant_id);
                })->inRandomOrder()->firstOr(function () {
                    return PaymentProvider::factory()->create();
                });

                $wallet->provider_id = $provider->id;
            }

            if (is_null($wallet->merchant_id)) {
                $merchant = PaymentMerchant::whereHas('providers', function ($query) use ($wallet) {
                    $query->where('payment_providers.id', $wallet->provider_id);
                })->inRandomOrder()->firstOr(function () use ($wallet) {
                    $merchant = PaymentMerchant::factory()->create();

                    $merchant->providers()->attach($wallet->provider_id, ['is_default' => true]);

                    return $merchant;
                });

                $wallet->merchant_id = $merchant->id;
            }
        });
    }
}
