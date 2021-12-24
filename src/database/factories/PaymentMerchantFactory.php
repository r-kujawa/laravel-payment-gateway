<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;

class PaymentMerchantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentMerchant::class;

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

        $name = $this->faker->unique()->company();

        return [
            'provider_id' => $provider->id,
            'name' => $name,
            'slug' => PaymentMerchant::slugify($name),
        ];
    }
}
