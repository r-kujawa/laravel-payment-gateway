<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\Database\Factories\WalletFactory;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'billable_type',
        'billable_id',
        'payment_provider_id',
        'token',
    ];
    
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

    public function paymentProvider()
    {
        return $this->belongsTo(PaymentProvider::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public static function findByToken(string $token)
    {
        return self::where('token', $token)->first();
    }
}
