<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentMerchantFactory;

class PaymentMerchant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PaymentMerchantFactory::new();
    }

    public function providers()
    {
        return $this->belongsToMany(config('payment.models.' . PaymentProvider::class, PaymentProvider::class), 'payment_merchant_provider', 'merchant_id', 'provider_id');
    }

    public function wallets()
    {
        return $this->hasMany(config('payment.models.' . Wallet::class, Wallet::class), 'merchant_id');
    }

    public function payment_methods()
    {
        return $this->hasManyThrough(config('payment.models.' . PaymentMethod::class, PaymentMethod::class), config('payment.models.' . Wallet::class, Wallet::class), 'merchant_id');
    }

    public static function slugify($name)
    {
        return preg_replace('/[^a-z0-9]+/i', '_', trim(strtolower($name)));
    }
}
