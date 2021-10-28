<?php

namespace rkujawa\LaravelPaymentGateway;

use rkujawa\LaravelPaymentGateway\Interfaces\PaymentManager;
use rkujawa\LaravelPaymentGateway\Interfaces\PaymentProcessor;
use rkujawa\LaravelPaymentGateway\Traits\ManagesPayments;
use rkujawa\LaravelPaymentGateway\Traits\ProcessesPayments;

class PaymentGateway extends PaymentService implements PaymentProcessor, PaymentManager
{
    use ProcessesPayments, ManagesPayments;
}
