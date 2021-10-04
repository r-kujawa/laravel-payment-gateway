<?php

namespace rkujawa\LaravelPaymentGateway\Contracts;

interface Buyer
{
    public function getId();
    public function getFullName(): string;
    public function getEmail(): string;
}
