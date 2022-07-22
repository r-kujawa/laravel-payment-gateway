<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;

class PaymentProviderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @return string
     */
    public function modelName()
    {
        return config('payment.models.' . PaymentProvider::class, PaymentProvider::class);
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $slug = PaymentProvider::slugify($this->faker->unique()->company());
        $studlySlug = Str::studly($slug);

        return [
            'slug' => $slug,
            'request_class' => "\App\Services\Payment\{$studlySlug}PaymentGateway",
            'request_class' => "\App\Services\Payment\{$studlySlug}PaymentResponse",
        ];
    }
}
