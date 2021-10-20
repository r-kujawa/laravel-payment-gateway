<?php

namespace rkujawa\LaravelPaymentGateway\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use rkujawa\LaravelPaymentGateway\database\Factories\PaymentTransactionFactory;
use rkujawa\LaravelPaymentGateway\Models\PaymentMethod;
use rkujawa\LaravelPaymentGateway\Models\PaymentTransaction;
use rkujawa\LaravelPaymentGateway\Tests\TestCase;

class PaymentTransactionTest extends TestCase
{
    use RefreshDatabase;

    public $transaction;

    /** @test  */
    public function payment_transactions_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('payment_transactions', [
                'id',
                'payload',
                'order_id',
                'created_at',
                'status_code',
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

    /** @test */
    public function should_be_possible_access_to_amount_property()
    {
        $transaction = PaymentTransaction::factory()->make();
        $this->assertNotNull($transaction->amount);
        $this->assertEquals(PaymentTransactionFactory::DEFAULT_AMOUNT_DLLS, $transaction->amount);
    }

    /** @test */
    public function set_amount_attribute_should_be_converted_and_setted_on_amount_cents_attribute()
    {
        $transaction = PaymentTransaction::factory()->make();
        $transaction->amount = 99;
        $this->assertEquals((99 * 100), $transaction->amount_cents);
        $this->assertEquals(99, $transaction->amount);
    }
}
