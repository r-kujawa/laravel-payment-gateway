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
        'provider_transaction_id',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function paymentProvider()
    {
        return $this->belongsTo(PaymentProvider::class);
    }

    public function getAmountAttribute()
    {
        return $this->amount_cents / 100; //this can be determined by config depending on currency
    }
}
