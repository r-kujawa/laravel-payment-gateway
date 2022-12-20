<?php

namespace rkujawa\LaravelPaymentGateway;

use rkujawa\LaravelPaymentGateway\Contracts\Merchantable;
use rkujawa\LaravelPaymentGateway\Contracts\PaymentRequestor;
use rkujawa\LaravelPaymentGateway\Contracts\Providable;
use rkujawa\LaravelPaymentGateway\Traits\PaymentRequests;

abstract class PaymentRequest implements PaymentRequestor
{
    use PaymentRequests;

    /**
     * The payment provider.
     *
     * @var \rkujawa\LaravelPaymentGateway\Contracts\Providable
     */
    protected $provider;

    /**
     * The payment merchant.
     *
     * @var \rkujawa\LaravelPaymentGateway\Contracts\Merchantable
     */
    protected $merchant;

    /**
     * @param  \rkujawa\LaravelPaymentGateway\Contracts\Providable $provider
     * @param  \rkujawa\LaravelPaymentGateway\Contracts\Merchantable $merchant
     */
    public function __construct(Providable $provider, Merchantable $merchant)
    {
        $this->provider = $provider;
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
