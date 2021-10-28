<?php

namespace rkujawa\LaravelPaymentGateway;

use rkujawa\LaravelPaymentGateway\Interfaces\PaymentProcessing;
use rkujawa\LaravelPaymentGateway\Traits\ProcessesPayments;

class PaymentProcessor extends PaymentService implements PaymentProcessing
{
    use ProcessesPayments;

    protected $service = PaymentService::PROCESSOR;
}
