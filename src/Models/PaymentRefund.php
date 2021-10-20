<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\database\Factories\PaymentRefundFactory;

class PaymentRefund extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'payload',
        'created_at',
        'status_code',
        'amount_cents',
        'provider_refund_id',
        'payment_transaction_id',
    ];

    public static function newFactory()
    {
        return PaymentRefundFactory::new();
    }

    public function paymentTransaction()
    {
        return $this->belongsTo(PaymentTransaction::class);
    }

    public function getAmountAttribute()
    {
        return $this->amount_cents / 100;//this can be determined by config depending on currency
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount_cents'] = $value * 100;
    }
}
