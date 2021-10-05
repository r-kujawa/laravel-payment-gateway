<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\DataTransferObjects\Contact;

class ContactDtoFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'company' => $this->faker->company . ' ' . $this->faker->randomElement(['LLC', 'INC', 'CORP']),
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
        ];
    }
}
