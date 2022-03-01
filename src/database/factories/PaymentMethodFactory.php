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
