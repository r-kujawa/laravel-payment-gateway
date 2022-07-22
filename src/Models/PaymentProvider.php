<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\Contracts\Providable;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentProviderFactory;

class PaymentProvider extends Model implements Providable
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
        return PaymentProviderFactory::new();
    }

    /**
     * Get the provider's id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the provider's slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get the merchant's the provider supports.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function merchants()
    {
        return $this->belongsToMany(config('payment.models.' . PaymentMerchant::class, PaymentMerchant::class), 'payment_merchant_provider', 'provider_id', 'merchant_id')->withPivot(['is_default'])->withTimestamps();
    }

    /**
     * Get the wallets the provider manages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wallets()
    {
        return $this->hasMany(config('payment.models.' . Wallet::class, Wallet::class), 'provider_id');
    }

    /**
     * Get the payment methods the provider manages.
     *
     * @return \\Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function payment_methods()
    {
        return $this->hasManyThrough(config('payment.models.' . PaymentMethod::class, PaymentMethod::class), config('payment.models.' . Wallet::class, Wallet::class), 'provider_id');
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
