<?php

namespace rkujawa\LaravelPaymentGateway;

use rkujawa\LaravelPaymentGateway\Interfaces\PaymentManagement;
use rkujawa\LaravelPaymentGateway\Traits\ManagesPayments;

class PaymentManager extends PaymentService implements PaymentManagement
{
    use ManagesPayments;

    protected $service = PaymentService::MANAGER;
}
