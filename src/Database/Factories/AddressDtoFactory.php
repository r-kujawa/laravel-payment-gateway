<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\DataTransferObjects\Address;

/**
 * @method static new()
 */
class AddressDtoFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        return [
            'country' => 'USA',
            'state' => $this->faker->state,
            'city' => $this->faker->city,
            'county' => $this->faker->word,
            'street1' => $this->faker->streetAddress,
            'street2' => $this->faker->randomElement([$this->faker->secondaryAddress, null]),
            'zip' => $this->faker->postcode
        ];
    }
}
