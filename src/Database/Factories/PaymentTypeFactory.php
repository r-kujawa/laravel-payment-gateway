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

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->faker->randomElement(static::getDefaults());

        if (PaymentType::where('slug', $type['slug'])->exists()) {
            $name = $this->faker->unique()->lexify('????');

            $type = [
                'name' => ucfirst($name),
                'display_name' => strtoupper($name),
                'slug' => static::getSlug($name),
            ];
        }

        return $type;
    }

    public static function getDefaults()
    {
        $types = [
            [
                'name' => 'Visa',
                'display_name' => 'VISA',
            ],
            [
                'name' => 'Mastercard',
                'display_name' => 'MasterCard',
            ],
            [
                'name' => 'American Express',
                'display_name' => 'AMEX',
            ],
            [
                'name' => 'Alipay',
                'display_name' => 'Alipay',
            ],
            [
                'name' => 'Apple Pay',
                'display_name' => 'Apple Pay',
            ],
            [
                'name' => 'Google Pay',
                'display_name' => 'Google Pay',
            ],
            [
                'name' => 'JCB',
                'display_name' => 'JCB',
            ],
            [
                'name' => 'Diners Club',
                'display_name' => 'Diners Club',
            ],
            [
                'name' => 'Discover',
                'display_name' => 'Discover',
            ],
            [
                'name' => 'Paypal',
                'display_name' => 'PayPal',
            ],
        ];

        return array_map(function ($type) {
            return array_merge($type, ['slug' => static::getSlug($type['name'])]);
        }, $types);
    } 

    private static function getSlug($name)
    {
        return preg_replace('/[^a-z]+/i', '_', trim(strtolower($name)));
    }
}
