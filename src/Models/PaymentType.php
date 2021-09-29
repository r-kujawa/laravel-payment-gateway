<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentTypeFactory;

class PaymentType extends Model
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
        return PaymentTypeFactory::new();
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
