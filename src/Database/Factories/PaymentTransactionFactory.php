<?php

namespace rkujawa\LaravelPaymentGateway\database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\Models\PaymentMethod;
use rkujawa\LaravelPaymentGateway\Models\PaymentTransaction;

class PaymentTransactionFactory extends Factory
{
    public const DEFAULT_AMOUNT_CENTS = 10000;
    public const DEFAULT_AMOUNT_DLLS = 100;

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
            'payload' => '{}',
            'order_id' => $this->faker->uuid(),
            'created_at' => now(),
            'status_code' => 69,
            'amount_cents' => self::DEFAULT_AMOUNT_CENTS,
            'payment_method_id' => $paymentMethod->id,
            //'payment_provider_id',
            'provider_transaction_id' => $this->faker->uuid(),
        ];
    }
}
