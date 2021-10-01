<?php

namespace rkujawa\LaravelPaymentGateway\database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use rkujawa\LaravelPaymentGateway\DataTransferObjects\Contact;

class ContactDtoFactory extends Factory
{
    protected $model = Contact::class;

    public function definition()
    {
        return [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'company' => $this->faker->company . ' ' . $faker->randomElement(['LLC', 'INC', 'CORP']),
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
        ];
    }
}