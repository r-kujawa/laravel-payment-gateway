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
            'number' => $this->faker->creditCardNumber,
            'expMonth' => $this->faker->month,
            'expYear' => (string) $this->faker->numberBetween(now()->year + 1, now()->year + 11),
            'code' => $this->faker->numerify('###'),
            'type' => $this->faker->creditCardType
        ];
    }
}