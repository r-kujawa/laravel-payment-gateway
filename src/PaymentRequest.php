<?php

namespace rkujawa\LaravelPaymentGateway;

use rkujawa\LaravelPaymentGateway\Contracts\PaymentRequestor;
use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;
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
     * The Payment provider.
     *
     * @var \rkujawa\LaravelPaymentGateway\Models\PaymentProvider
     */
    protected $provider;

    /**
     * @param  \rkujawa\LaravelPaymentGateway\Models\PaymentMerchant|null $merchant
     * @param  \rkujawa\LaravelPaymentGateway\Models\PaymentProvider|null $provider
     */
    public function __construct(PaymentMerchant $merchant = null, PaymentProvider $provider = null)
    {
        $this->merchant = $merchant;
        $this->provider = $provider;

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
