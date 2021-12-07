<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentMethodFactory;

class PaymentMethod extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = [
        'token',
        'exp_month',
        'exp_year',
    ];

    protected $appends = ['expiration_date'];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PaymentMethodFactory::new();
    }

    public function setLastDigitsAttribute($value)
    {
        $this->attributes['last_digits'] = substr(preg_replace('/[^0-9]/', '', $value), -4);
    }

    public function setFirstNameAttribute($value)
    {
        if (!is_null($value)) {
            $this->attributes['first_name'] = ucwords(strtolower(trim($value)));
        }
    }

    public function setLastNameAttribute($value)
    {
        if (!is_null($value)) {
            $this->attributes['last_name'] = ucwords(strtolower(trim($value)));
        }
    }

    public function getCardholderAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getExpirationDateAttribute()
    {
        return $this->getExpirationDate();
    }

    public function getProviderAttribute()
    {
        return $this->wallet->provider;
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function getExpirationDate(string $separator = '/', $yearFirst = false)
    {
        if ($yearFirst) {
            return $this->exp_year . $separator . $this->exp_month;
        }

        return $this->exp_month . $separator . $this->exp_year;
    }

    public static function findByToken(string $token)
    {
        return self::where('token', $token)->first();
    }
}
