<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentTransactionFactory;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'payload' => 'array',
        'references' => 'array',
    ];

    public static function newFactory()
    {
        return PaymentTransactionFactory::new();
    }

    public function paymentMethod()
    {
        return $this->belongsTo(config('payment.models.' . PaymentMethod::class, PaymentMethod::class));
    }

    public function provider()
    {
        return $this->belongsTo(config('payment.models.' . PaymentProvider::class, PaymentProvider::class));
    }

    public function merchant()
    {
        return $this->belongsTo(config('payment.models.' . PaymentMerchant::class, PaymentMerchant::class));
    }
}
