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
        return [
            'reference_id' => $this->faker->uuid(),
            'currency' => $this->faker->currencyCode(),
            'type' => $this->faker->randomElement([PaymentRefund::VOID, PaymentRefund::REFUND]),
            'status_code' => 69, // TODO: Determine the status codes.
            'payload' => [],
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (PaymentRefund $refund) {
            if (is_null($refund->transaction_id)) {
                $transaction = PaymentTransaction::factory()->create();

                $refund->transaction_id = $transaction->id;
            }

            if (is_null($refund->amount_cents)) {
                $refund->amount_cents = $refund->transaction->amount_cents;
            }
        });
    }
}
