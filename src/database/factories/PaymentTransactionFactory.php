<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
        $paymentMethod = PaymentMethod::inRandomOrder()->firstOr(function () {
            return PaymentMethod::factory()->create();
        });

        return [
            'provider_id' => $paymentMethod->provider->id,
            'reference_id' => $this->faker->uuid(),
            'amount_cents' => $this->faker->numberBetween(1, 999) * 100,
            'currency' => $this->faker->currencyCode(),
            'payload' => [],
            'status_code' => 69, // TODO: Determine the status codes.
            'payment_method_id' => $paymentMethod->id,
        ];
    }
}
