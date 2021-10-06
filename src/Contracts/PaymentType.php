<?php

namespace rkujawa\LaravelPaymentGateway\Contracts;

interface PaymentType
{
    public const TOKEN = 'token';
    public const CARD = 'card';

    public function getPaymentType(): string;
}