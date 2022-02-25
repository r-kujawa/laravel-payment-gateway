<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\Database\Factories\WalletFactory;
use rkujawa\LaravelPaymentGateway\Models\Traits\WalletRequests;

class Wallet extends Model
{
    use HasFactory;
    use WalletRequests;

    protected $guarded = ['id'];

    protected $hidden = ['token'];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return WalletFactory::new();
    }

    public function billable()
    {
        return $this->morphTo();
    }

    public function provider()
    {
        return $this->belongsTo(config('payment.models.' . PaymentProvider::class, PaymentProvider::class));
    }

    public function merchant()
    {
        return $this->belongsTo(config('payment.models.' . PaymentMerchant::class, PaymentMerchant::class));
    }

    public function paymentMethods()
    {
        return $this->hasMany(config('payment.models.' . PaymentMethod::class, PaymentMethod::class));
    }
}
