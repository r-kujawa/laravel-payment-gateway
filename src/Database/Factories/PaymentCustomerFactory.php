<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\Models\PaymentCustomer;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;

class PaymentCustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentCustomer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $provider = PaymentProvider::inRandomOrder()->firstOr(function () {
            return PaymentProvider::factory()->create();
        });

        return [
            'provider_id' => $provider->id,
            'token' => $this->faker->uuid(),
        ];
    }
}
