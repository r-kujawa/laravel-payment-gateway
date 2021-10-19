<?php

namespace rkujawa\LaravelPaymentGateway\database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\Models\PaymentTransaction;

class PaymentRefundFactory extends Factory
{

    public function definition()
    {
        $paymentTransaction = PaymentTransaction::factory()->make();

        return [
            'payment_transaction_id' => $paymentTransaction->id,
            'provider_refund_id' => $this->faker->uuid(),
            'amount_cents' => 9999,//100 dlls
            'created_at' => now(),
            'payload' => '{}',
            'type' => 'refund'
        ];
    }
}
