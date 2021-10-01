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
            'firstName' => $faker->firstName,
            'lastName' => $faker->lastName,
            'company' => $faker->company . ' ' . $faker->randomElement(['LLC', 'INC', 'CORP']),
            'email' => $faker->email,
            'phone' => $faker->phoneNumber,
        ];
    }
}