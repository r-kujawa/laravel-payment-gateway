<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentProviderFactory;

class PaymentProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PaymentProviderFactory::new();
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class, 'provider_id');
    }
}
