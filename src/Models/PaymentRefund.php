<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentRefundFactory;

class PaymentRefund extends Model
{
    use HasFactory;

    const VOID = 'void';
    const REFUND = 'refund';

    protected $guarded = ['id'];

    protected $casts = [
        'payload' => 'array',
    ];

    public static function newFactory()
    {
        return PaymentRefundFactory::new();
    }

    public function transaction()
    {
        return $this->belongsTo(config('payment.models.' . PaymentTransaction::class, PaymentTransaction::class));
    }
}
