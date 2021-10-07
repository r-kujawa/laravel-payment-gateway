<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

interface BillableContract
{
    public function getId(): string;
    public function getEmail(): string;
    public function getFullName(): string;
}
