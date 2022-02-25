<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentTypeFactory;

class PaymentType extends Model
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
        return PaymentTypeFactory::new();
    }

    /**
     * Get the payment type's parent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(config('payment.models.' . PaymentType::class, PaymentType::class));
    }

    /**
     * Get the payment methods that inherit this type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentMethods()
    {
        return $this->hasMany(config('payment.models.' . PaymentMethod::class, PaymentMethod::class), 'type_id');
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
