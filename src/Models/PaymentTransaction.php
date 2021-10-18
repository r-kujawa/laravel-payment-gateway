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
        'payment_method_id',
        'payment_provider_id',
        'gateway_transaction_id',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function provider()
    {
        return $this->belongsTo(PaymentProvider::class);
    }
}
