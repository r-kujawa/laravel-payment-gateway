<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\Models\PaymentRefund;
use rkujawa\LaravelPaymentGateway\Models\PaymentTransaction;
use rkujawa\LaravelPaymentGateway\PaymentStatus;

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
            'type' => ($refund = $this->faker->randomElement([PaymentRefund::VOID, PaymentRefund::REFUND])),
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

            if (is_null($refund->amount)) {
                $refund->amount = $refund->transaction->amount;
            }
        });
    }
}
