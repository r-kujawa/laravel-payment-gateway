<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'amount',
        'payload',
        'order_id',
        'created_at',
        'provider_id',
        'payment_method_id',
        'gateway_transaction_id',
    ];

}