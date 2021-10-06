<?php

namespace rkujawa\LaravelPaymentGateway\Contracts;

interface Buyer
{
    public function getId(): string;
    public function getEmail(): string;
    public function getFullName(): string;
}