<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\Models\PaymentType;

class PaymentTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentType::class;

    public const DEFAULTS = [
        [
            'name' => 'Visa',
            'display_name' => 'VISA',
            'slug' => 'visa',
        ],
        [
            'name' => 'Mastercard',
            'display_name' => 'MasterCard',
            'slug' => 'mastercard',
        ],
        [
            'name' => 'American Express',
            'display_name' => 'AMEX',
            'slug' => 'amex',
        ],
        [
            'name' => 'Alipay',
            'display_name' => 'Alipay',
            'slug' => 'alipay',
        ],
        [
            'name' => 'Apple Pay',
            'display_name' => 'Apple Pay',
            'slug' => 'apple_pay',
        ],
        [
            'name' => 'Google Pay',
            'display_name' => 'Google Pay',
            'slug' => 'google_pay',
        ],
        [
            'name' => 'JCB',
            'display_name' => 'JCB',
            'slug' => 'jcb',
        ],
        [
            'name' => 'Diners Club',
            'display_name' => 'Diners Club',
            'slug' => 'diners_club',
        ],
        [
            'name' => 'Discover',
            'display_name' => 'Discover',
            'slug' => 'discover',
        ],
        [
            'name' => 'Paypal',
            'display_name' => 'PayPal',
            'slug' => 'paypal',
        ],
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique()->lexify('????');

        return [
            'name' => ucfirst($name),
            'display_name' => strtoupper($name),
            'slug' => PaymentType::getSlug($name),
        ];
    }

    public function real()
    {
        return $this->state(function () {
            $type = collect(static::DEFAULTS)->whereNotIn('slug', PaymentType::all()->pluck('slug'))->first();

            if (is_null($type))
            {
                return [];
            }

            return $type;
        });
    }
}
