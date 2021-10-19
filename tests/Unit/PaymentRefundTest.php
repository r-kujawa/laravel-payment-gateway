<?php

namespace rkujawa\LaravelPaymentGateway\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
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
                    'amount_cents',
                    'provider_refund_id',
                    'payment_transaction_id',
                ]
            )
        );
    }
}