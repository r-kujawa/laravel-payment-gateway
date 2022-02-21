<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\Models\Wallet;
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
        $exp['year'] = (string) $this->faker->numberBetween(now()->year, now()->year + 11);
        $exp['month'] = (string) $this->faker->numberBetween($exp['year'] === now()->year ? now()->month : 1, 12);

        return [
            'token' => $this->faker->uuid(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'last_digits' => $this->faker->numerify('####'),
            'exp_month' => str_pad($exp['month'], 2, '0', STR_PAD_LEFT),
            'exp_year' => $exp['year'],
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (PaymentMethod $paymentMethod) {
            if(is_null($paymentMethod->wallet_id)) {
                $wallet = Wallet::inRandomOrder()->firstOr(function () {
                    return Wallet::factory()->create();
                });

                $paymentMethod->wallet_id = $wallet->id;
            }

            if (is_null($paymentMethod->type_id)) {
                $type = PaymentType::inRandomOrder()->firstOr(function () {
                    return PaymentType::factory()->create();
                });

                $paymentMethod->type_id = $type->id;
            }
        });
    }
}
