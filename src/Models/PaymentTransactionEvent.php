<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentTransactionEventFactory;

class PaymentTransactionEvent extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'reference',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'details' => 'array',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public static function newFactory()
    {
        return PaymentTransactionEventFactory::new();
    }

    /**
     * Get the event's original transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(config('payment.models.' . PaymentTransaction::class, PaymentTransaction::class));
    }

    /**
     * Get the transactions event's provider.
     *
     * @return \rkujawa\LaravelPaymentGateway\Models\PaymentProvider
     */
    public function getProviderAttribute()
    {
        return $this->transaction->provider;
    }

    /**
     * Get the transaction event's merchant.
     *
     * @return \rkujawa\LaravelPaymentGateway\Models\PaymentMerchant
     */
    public function getMerchantAttribute()
    {
        return $this->transaction->merchant;
    }
}
