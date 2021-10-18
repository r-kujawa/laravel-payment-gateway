<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRefund extends Model
{
    protected $fillable = ['provider_refund_id', 'payment_transaction_id', 'amount', 'payload', 'created_at'];

    public function paymentTransactions()
    {
        return $this->belongsTo(PaymentTransaction::class);
    }
}
