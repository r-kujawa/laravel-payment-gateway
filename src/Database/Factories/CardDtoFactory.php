<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\DataTransferObjects\Card;
use rkujawa\LaravelPaymentGateway\Models\PaymentType;

class CardDtoFactory extends Factory
{
    protected $model = Card::class;

    public function definition(): array
    {
        return [
            'number' => $this->faker->creditCardNumber,
            'expMonth' => $this->faker->month,
            'expYear' => (string) $this->faker->numberBetween(now()->year + 1, now()->year + 11),
            'code' => $this->faker->numerify('###'),
            'type' => $this->getCreditCardType()
        ];
    }

    private function getCreditCardType(): string
    {
        return PaymentType::inRandomOrder()->first()->name;
    }
}
