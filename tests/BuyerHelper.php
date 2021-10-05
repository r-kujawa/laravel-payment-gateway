<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use Orchestra\Testbench\Factories\UserFactory;
use rkujawa\LaravelPaymentGateway\Contracts\Buyer;

class BuyerHelper implements Buyer
{
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public $buyer;

    public function __construct()
    {
        $this->buyer = (new UserFactory())->create();
    }

    public function getId(): string
    {
        return $this->buyer->id;
    }

    public function getFullName(): string
    {
        return $this->buyer->name;
    }

    public function getEmail(): string
    {
        return $this->buyer->email;
    }
}
