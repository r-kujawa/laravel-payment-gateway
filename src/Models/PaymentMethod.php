<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentMethodFactory;
use rkujawa\LaravelPaymentGateway\PaymentGateway;

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
     * The payment method's pre-configured gateway.
     *
     * @var \rkujawa\LaravelPaymentGateway\PaymentGateway
     */
    private $paymentGateway;

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

    public function getExpirationDate(string $separator = '/', $yearFirst = false)
    {
        if ($yearFirst) {
            return $this->exp_year . $separator . $this->exp_month;
        }

        return $this->exp_month . $separator . $this->exp_year;
    }

    /**
     * Retrieve the payment method's configured gateway.
     *
     * @return \rkujawa\LaravelPaymentGateway\PaymentGateway
     */
    public function getGatewayAttribute()
    {
        if (! isset($this->paymentGateway)) {
            $this->paymentGateway = (new PaymentGateway)
                ->provider($this->wallet->provider)
                ->merchant($this->wallet->merchant);
        }
        
        return $this->paymentGateway;
    }

    /**
     * Request the payment method details from the provider.
     *
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentManagerResponse
     */
    public function requestDetails()
    {
        return $this->gateway->getPaymentMethod($this);
    }

    /**
     * Request the provider to update the payment method's details.
     *
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentManagerResponse
     */
    public function requestUpdate($data)
    {
        return $this->gateway->updatePaymentMethod($this, $data);
    }

    /**
     * Request the provider to remove the payment method from their system.
     *
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentManagerResponse
     */
    public function requestRemoval()
    {
        return $this->gateway->deletePaymentMethod($this);
    }
}
