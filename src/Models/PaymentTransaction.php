<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\database\Factories\PaymentTransactionFactory;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payload',
        'order_id',
        'created_at',
        'amount_cents',
        'payment_method_id',
        //'payment_provider_id',
        'provider_transaction_id',
    ];

    public static function newFactory()
    {
        return PaymentTransactionFactory::new();
    }

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
        return $this->amount_cents / 100; //this can be determined by config depending on a currency
    }
}
