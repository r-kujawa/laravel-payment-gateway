<?php

namespace rkujawa\LaravelPaymentGateway;

use rkujawa\LaravelPaymentGateway\Interfaces\PaymentManagement;
use rkujawa\LaravelPaymentGateway\Interfaces\PaymentProcessing;
use rkujawa\LaravelPaymentGateway\Traits\ManagesPayments;
use rkujawa\LaravelPaymentGateway\Traits\ProcessesPayments;

class PaymentGateway extends PaymentService implements PaymentProcessing, PaymentManagement
{
    use ProcessesPayments, ManagesPayments;
}
