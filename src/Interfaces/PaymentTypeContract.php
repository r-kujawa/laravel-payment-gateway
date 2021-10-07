<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

interface PaymentTypeContract
{
    public const TOKEN = 'token';
    public const CARD = 'card';

    public function getPaymentType(): string;
}
