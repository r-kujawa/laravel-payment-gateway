<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\Models\PaymentRefund;
use rkujawa\LaravelPaymentGateway\Models\PaymentTransaction;

class PaymentRefundFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentRefund::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $paymentTransaction = PaymentTransaction::inRandomOrder()->firstOr(function () {
            return PaymentTransaction::factory()->create();
        });

        return [
            'reference_id' => $this->faker->uuid(),
            'transaction_id' => $paymentTransaction->id,
            'amount_cents' => $this->faker->numberBetween(1, 999) * 100,
            'currency' => $this->faker->currencyCode(),
            'type' => $this->faker->randomElement([PaymentRefund::VOID, PaymentRefund::REFUND]),
            'status_code' => 69, // TODO: Determine the status codes.
            'payload' => [],
        ];
    }
}
