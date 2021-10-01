<?php

namespace rkujawa\LaravelPaymentGateway\database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\DataTransferObjects\Card;

class CardDtoFactory extends Factory
{
    protected $model = Card::class;

    public function definition()
    {
        return [
            'number' => $faker->creditCardNumber,
            'expMonth' => $faker->month,
            'expYear' => (string) $faker->numberBetween(now()->year + 1, now()->year + 11),
            'code' => $faker->numerify('###'),
            'type' => $faker->creditCardType
        ];
    }
}