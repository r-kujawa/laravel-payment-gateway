<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentProvider extends Model
{
    protected $fillable = ['name'];

    const ID = [
        'authorize' => 1,
        'braintree' => 2
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function paymentCustomers()
    {
        return $this->hasMany(PaymentCustomer::class, 'provider_id');
    }

    public function paymentMethods()
    {
        return $this->hasManyThrough(PaymentMethod::class, PaymentCustomer::class);
    }
}
