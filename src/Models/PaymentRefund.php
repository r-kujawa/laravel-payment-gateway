<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentRefundFactory;
use rkujawa\LaravelPaymentGateway\Traits\AmountConverter;

class PaymentRefund extends Model
{
    use HasFactory, AmountConverter;

    const VOID = 'void';
    const REFUND = 'refund';

    protected $guarded = ['id'];

    protected $casts = [
        'payload' => 'array',
    ];

    protected $appends = ['amount'];

    public static function newFactory()
    {
        return PaymentRefundFactory::new();
    }

    public function transaction()
    {
        return $this->belongsTo(config('payment.models.' . PaymentTransaction::class, PaymentTransaction::class));
    }
}
