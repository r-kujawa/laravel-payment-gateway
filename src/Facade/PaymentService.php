<?php

namespace rkujawa\LaravelPaymentGateway\Facade;

use Illuminate\Support\Facades\Facade;

class PaymentService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PaymentService';
    }
}
