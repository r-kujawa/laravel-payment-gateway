<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\database\Factories\PaymentTransactionFactory;
use rkujawa\LaravelPaymentGateway\Traits\AmountConverter;

class PaymentTransaction extends Model
{
    use HasFactory, AmountConverter;

    protected $fillable = [
        'payload',
        'order_id',
        'created_at',
        'status_code',
        'amount_cents',
        'payment_method_id',
        //'payment_provider_id',
        'provider_transaction_id',
    ];
    public $timestamps = false;

    public static function newFactory()
    {
        return PaymentTransactionFactory::new();
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /*public function paymentProvider()
    {
        return $this->belongsTo(PaymentProvider::class);
    }*/
}
