<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\database\Factories\PaymentRefundFactory;
use rkujawa\LaravelPaymentGateway\Traits\AmountConverter;

class PaymentRefund extends Model
{
    use HasFactory, AmountConverter;

    public const TYPE_VOID = 'void';
    public const TYPE_REFUND = 'refund';

    public $timestamps = false;

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
}
