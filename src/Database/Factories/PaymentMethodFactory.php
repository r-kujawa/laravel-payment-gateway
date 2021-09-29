<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\Models\PaymentCustomer;
use rkujawa\LaravelPaymentGateway\Models\PaymentMethod;
use rkujawa\LaravelPaymentGateway\Models\PaymentType;

class PaymentMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $customer = PaymentCustomer::inRandomOrder()->firstOr(function () {
            return PaymentCustomer::factory()->create();
        });

        $type = PaymentType::inRandomOrder()->firstOr(function () {
            return PaymentType::factory()->create();
        });

        $exp['year'] = (string) $this->faker->numberBetween(now()->year, now()->year + 11);
        $exp['month'] = (string) $this->faker->numberBetween($exp['year'] === now()->year ? now()->month : 1, 12);

        return [
            'customer_id' => $customer->id,
            'token' => $this->faker->uuid(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'last_digits' => $this->faker->numerify('####'),
            'exp_month' => str_pad($exp['month'], 2, '0', STR_PAD_LEFT),
            'exp_year' => $exp['year'],
            'type_id' => $type->id,
        ];
    }
}
