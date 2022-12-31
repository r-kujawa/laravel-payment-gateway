<?php

namespace rkujawa\LaravelPaymentGateway\Database\Seeders;

use Illuminate\Database\Seeder;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentTypeFactory;
use rkujawa\LaravelPaymentGateway\Models\PaymentType;

class PaymentTypeSeeder extends Seeder
{
    public function run()
    {
        foreach (PaymentTypeFactory::DEFAULTS as $paymentType) {
            PaymentType::firstOrCreate(
                [
                    'slug' => $paymentType['slug']
                ],
                [
                    'name' => $paymentType['name'],
                ]
            );
        }
    }
}