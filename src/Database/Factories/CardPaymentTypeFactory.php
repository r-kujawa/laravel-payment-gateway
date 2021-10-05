<?php

namespace rkujawa\LaravelPaymentGateway\Database\Factories;

use rkujawa\LaravelPaymentGateway\Types\CardPayment;

class CardPaymentTypeFactory
{
    public static function getInstance()
    {
        return new CardPayment(
            CardDtoFactory::new()->make(),
            ContactDtoFactory::new()->make(),
            AddressDtoFactory::new()->make()
        );
    }
}
