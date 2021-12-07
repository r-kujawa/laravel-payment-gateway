<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentTransactionFactory;
use rkujawa\LaravelPaymentGateway\Traits\AmountConverter;

class PaymentTransaction extends Model
{
    use HasFactory, AmountConverter;

    protected $guarded = ['id'];

    protected $casts = [
        'payload' => 'array',
        'references' => 'array',
    ];

    protected $appends = ['amount'];

    public static function newFactory()
    {
        return PaymentTransactionFactory::new();
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function provider()
    {
        return $this->belongsTo(PaymentProvider::class);
    }
}
