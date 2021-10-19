<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRefund extends Model
{
    protected $fillable = [
        'payment_transaction_id',
        'provider_refund_id',
        'amount_cents',
        'created_at',
        'payload',
        'type'
    ];

    public function paymentTransactions()
    {
        return $this->belongsTo(PaymentTransaction::class);
    }

    public function getAmountAttribute()
    {
        return $this->amount_cents / 100;//this can be determined by config depending on currency
    }
}
