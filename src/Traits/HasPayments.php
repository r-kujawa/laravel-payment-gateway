<?php

namespace rkujawa\LaravelPaymentGateway\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use rkujawa\LaravelPaymentGateway\Models\PaymentCustomer;
use rkujawa\LaravelPaymentGateway\Models\PaymentMethod;

/**
 * This trait should be a polymorphic relation to PaymentCustomer
 * so it could be used for multiple models, but for dev reasons
 * it will be left as a single model relation for the Client model.
 */
trait HasPayments
{
    /**
     * Retrieves the current model's PaymentCustomer model.
     * Note: This should be a one to one relationship but for migration
     * issues we will be supporting one to many relation until we deduplicate
     * the customer profiles (It can only be done when migrating to Braintree).
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function paymentCustomers(): HasMany
    {
        return $this->hasMany(PaymentCustomer::class);
    }

    /**
     * Retrieves all of the current model's PaymentMethod models.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function paymentMethods(): HasManyThrough
    {
        return $this->hasManyThrough(PaymentMethod::class, PaymentCustomer::class);
    }
}
