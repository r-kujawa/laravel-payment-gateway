<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

interface PaymentType
{
    public const TOKEN = 'token';
    public const CARD = 'card';

    public function getPaymentType(): string;
}
