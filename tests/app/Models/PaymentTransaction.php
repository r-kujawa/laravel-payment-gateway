<?php

namespace rkujawa\LaravelPaymentGateway\Tests\App\Models;

use rkujawa\LaravelPaymentGateway\Models\PaymentTransaction as Model;
use rkujawa\LaravelPaymentGateway\Traits\AmountConverter;

class PaymentTransaction extends Model
{
    use AmountConverter;

    protected $fillable = [
        'provider_id',
        'reference_id',
        'amount_cents',
        'amount',
        'currency',
        'payment_method_id',
        'status_code',
        'payload',
        'references',
    ];
}
