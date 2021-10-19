<?php

namespace rkujawa\LaravelPaymentGateway\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use rkujawa\LaravelPaymentGateway\Models\PaymentMethod;
use rkujawa\LaravelPaymentGateway\Models\PaymentTransaction;
use rkujawa\LaravelPaymentGateway\Tests\TestCase;

class PaymentTransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function payment_transactions_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('payment_transactions', [
                'id',
                'status',
                'payload',
                'order_id',
                'created_at',
                'amount_cents',
                'payment_method_id',
                'provider_transaction_id',
            ])
        );
    }

    /** @test */
    public function a_transaction_has_one_payment_method()
    {
        $transaction = PaymentTransaction::factory()->make();
        $paymentMethod = $transaction->paymentMethod;
        $this->assertNotNull($paymentMethod);
        $this->assertInstanceOf(PaymentMethod::class, $paymentMethod);
    }
}
