<?php

namespace rkujawa\LaravelPaymentGateway;

use rkujawa\LaravelPaymentGateway\Contracts\PaymentRequestor;
use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
use rkujawa\LaravelPaymentGateway\Traits\PaymentRequests;

abstract class PaymentRequest implements PaymentRequestor
{
    use PaymentRequests;

    /**
     * The payment merchant.
     *
     * @var \rkujawa\LaravelPaymentGateway\Models\PaymentMerchant
     */
    protected $merchant;

    /**
     * @param  \rkujawa\LaravelPaymentGateway\Models\PaymentMerchant|null $merchant
     */
    public function __construct(PaymentMerchant $merchant = null)
    {
        $this->merchant = $merchant;

        $this->setUp();
    }

    /**
     * Set up the request.
     *
     * @return void
     */
    protected function setUp()
    {
        //
    }
}
