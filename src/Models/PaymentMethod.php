<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentMethodFactory;
use rkujawa\LaravelPaymentGateway\Models\Traits\PaymentMethodRequests;

=

class PaymentMethod extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PaymentMethodRequests;

    protected $guarded = ['id'];

    protected $hidden = [
        'token',
        'details',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PaymentMethodFactory::new();
    }

    public function getProviderAttribute()
    {
        return $this->wallet->provider;
    }

    public function getMerchantAttribute()
    {
        return $this->wallet->merchant;
    }

    public function wallet()
    {
        return $this->belongsTo(config('payment.models.' . Wallet::class, Wallet::class));
    }

    public function type()
    {
        return $this->belongsTo(config('payment.models.' . PaymentType::class, PaymentType::class));
    }

    public function transactions()
    {
        return $this->hasMany(config('payment.models.' . PaymentTransaction::class, PaymentTransaction::class));
    }
}
