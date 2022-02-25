<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentMerchantFactory;

class PaymentMerchant extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
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

    /**
     * Get the providers that the merchant belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function providers()
    {
        return $this->belongsToMany(config('payment.models.' . PaymentProvider::class, PaymentProvider::class), 'payment_merchant_provider', 'merchant_id', 'provider_id')->withPivot(['is_default'])->withTimestamps();
    }

    /**
     * Get the merchant's wallets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wallets()
    {
        return $this->hasMany(config('payment.models.' . Wallet::class, Wallet::class), 'merchant_id');
    }

    /**
     * Get the payment methods stored under the merchant's account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function payment_methods()
    {
        return $this->hasManyThrough(config('payment.models.' . PaymentMethod::class, PaymentMethod::class), config('payment.models.' . Wallet::class, Wallet::class), 'merchant_id');
    }

    /**
     * Generate a slug based off a string that follows a set of rules to make it valid.
     *
     * @param string $name
     * @return string
     */
    public static function slugify($name)
    {
        return preg_replace('/[^a-z0-9]+/i', '_', trim(strtolower($name)));
    }
}
