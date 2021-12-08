<?php

namespace rkujawa\LaravelPaymentGateway\Tests\App\Models;

use rkujawa\LaravelPaymentGateway\Models\PaymentRefund as Model;
use rkujawa\LaravelPaymentGateway\Traits\AmountConverter;

class PaymentRefund extends Model
{
    use AmountConverter;

    protected $fillable = [
        'reference_id',
        'transaction_id',
        'amount_cents',
        'amount',
        'currency',
        'type',
        'status_code',
        'payload',
    ];
}
