<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\database\Factories\PaymentRefundFactory;
use rkujawa\LaravelPaymentGateway\Traits\AmountConverter;

class PaymentRefund extends Model
{
    use HasFactory, AmountConverter;

    const VOID = 'void';
    const REFUND = 'refund';

    protected $fillable = [
        'reference_id',
        'transaction_id',
        'amount_cents',
        'currency',
        'type',
        'status_code',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public static function newFactory()
    {
        return PaymentRefundFactory::new();
    }

    public function transaction()
    {
        return $this->belongsTo(PaymentTransaction::class);
    }
}
