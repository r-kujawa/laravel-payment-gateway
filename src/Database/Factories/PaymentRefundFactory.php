<?php

namespace rkujawa\LaravelPaymentGateway\database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\Models\PaymentRefund;
use rkujawa\LaravelPaymentGateway\Models\PaymentTransaction;

class PaymentRefundFactory extends Factory
{
    public const DEFAULT_AMOUNT_CENTS = 9900;
    public const DEFAULT_AMOUNT_DLLS = 99;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentRefund::class;


    public function definition()
    {
        $paymentTransaction = PaymentTransaction::factory()->create();

        return [
            'payment_transaction_id' => $paymentTransaction->id,
            'provider_refund_id' => $this->faker->uuid(),
            'amount_cents' => self::DEFAULT_AMOUNT_CENTS, //99 dlls
            'status_code' => 69, //tbd
            'created_at' => now(),
            'payload' => '{}',
            'type' => 'refund'
        ];
    }
}
