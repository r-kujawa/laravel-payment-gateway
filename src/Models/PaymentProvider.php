<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentProviderFactory;

class PaymentProvider extends Model
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
        return PaymentProviderFactory::new();
    }

    public function merchants()
    {
        return $this->belongsToMany(config('payment.models.' . PaymentMerchant::class, PaymentMerchant::class), 'payment_merchant_provider', 'provider_id', 'merchant_id')->withPivot(['is_default'])->withTimestamps();
    }

    public function wallets()
    {
        return $this->hasMany(config('payment.models.' . Wallet::class, Wallet::class), 'provider_id');
    }

    public function payment_methods()
    {
        return $this->hasManyThrough(config('payment.models.' . PaymentMethod::class, PaymentMethod::class), config('payment.models.' . Wallet::class, Wallet::class), 'provider_id');
    }

    public static function slugify($name)
    {
        return preg_replace('/[^a-z0-9]+/i', '_', trim(strtolower($name)));
    }
}
