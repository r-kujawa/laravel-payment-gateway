<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;

class PaymentMerchantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @return string
     */
    public function modelName()
    {
        return config('payment.models.' . PaymentMerchant::class, PaymentMerchant::class);
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique()->company();

        return [
            'name' => $name,
            'slug' => PaymentMerchant::slugify($name),
        ];
    }
}
