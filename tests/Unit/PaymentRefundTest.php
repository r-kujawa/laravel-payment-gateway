<?php

namespace rkujawa\LaravelPaymentGateway\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use rkujawa\LaravelPaymentGateway\database\Factories\PaymentRefundFactory;
use rkujawa\LaravelPaymentGateway\Models\PaymentRefund;
use rkujawa\LaravelPaymentGateway\Models\PaymentTransaction;
use rkujawa\LaravelPaymentGateway\Tests\TestCase;

class PaymentRefundTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function payment_refunds_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('payment_refunds',
                [
                    'id',
                    'type',
                    'payload',
                    'created_at',
                    'status_code',
                    'amount_cents',
                    'provider_refund_id',
                    'payment_transaction_id',
                ]
            )
        );
    }

    /** @test */
    public function a_refund_belongs_to_a_transaction()
    {
        $refund = PaymentRefund::factory()->make();
        $transaction = $refund->paymentTransaction;
        $this->assertNotNull($transaction);
        $this->assertInstanceOf(PaymentTransaction::class, $transaction);
    }

    /** @test */
    public function should_be_possible_to_access_amount_property()
    {
        $refund = PaymentRefund::factory()->make();
        $this->assertNotNull($refund->amount);
        $this->assertEquals(PaymentRefundFactory::DEFAULT_AMOUNT_DLLS, $refund->amount);
        $this->assertEquals(PaymentRefundFactory::DEFAULT_AMOUNT_CENTS, $refund->amount_cents);
    }

    /** @test */
    public function set_amount_attribute_should_be_converted_and_setted_on_amount_cents_attribute()
    {
        $refund = PaymentRefund::factory()->make();
        $paymentTransaction = $refund->paymentTransaction;
        $refund->amount = $paymentTransaction->amount;
        $this->assertEquals(($paymentTransaction->amount * 100), $refund->amount_cents);
        $this->assertEquals($paymentTransaction->amount, $refund->amount);
    }
}
