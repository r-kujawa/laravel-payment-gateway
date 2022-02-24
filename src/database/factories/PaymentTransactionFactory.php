<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
use rkujawa\LaravelPaymentGateway\Models\PaymentMethod;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;
use rkujawa\LaravelPaymentGateway\Models\PaymentTransaction;

class PaymentTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'reference_id' => $this->faker->uuid(),
            'amount_cents' => $this->faker->numberBetween(1, 999) * 100,
            'currency' => $this->faker->currencyCode(),
            'payload' => [],
            'status_code' => 69, // TODO: Determine the status codes.
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (PaymentTransaction $transaction) {
            if (is_null($transaction->provider_id)) {
                $provider = ! is_null($transaction->payment_method_id)
                    ? $transaction->paymentMethod->provider
                    : PaymentProvider::whereHas('merchants', function ($query) use ($transaction) {
                        $query->where('payment_merchants.id', $transaction->merchant_id);
                    })->inRandomOrder()->firstOr(function ()  {
                        return PaymentProvider::factory()->create();
                    });

                $transaction->provider_id = $provider->id;
            }

            if (is_null($transaction->merchant_id)) {
                $merchant = ! is_null($transaction->payment_method_id)
                    ? $transaction->paymentMethod->merchant
                    : PaymentMerchant::whereHas('providers', function ($query) use ($transaction) {
                        $query->where('payment_providers.id', $transaction->provider_id);
                    })->inRandomOrder()->firstOr(function () use ($transaction) {
                        $merchant = PaymentMerchant::factory()->create();

                        $merchant->providers()->attach($transaction->provider_id, ['is_default' => true]);

                        return $merchant;
                    });

                $transaction->merchant_id = $merchant->id;
            }
        });
    }
}
