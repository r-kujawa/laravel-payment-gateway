<?php

namespace rkujawa\LaravelPaymentGateway\Tests\Unit;

use rkujawa\LaravelPaymentGateway\Facades\PaymentService;
use rkujawa\LaravelPaymentGateway\Tests\TestCase;

class FacadeTest extends TestCase
{
    public function test_facade_get_provider_method()
    {
        PaymentService::getProvider();
    }

}
