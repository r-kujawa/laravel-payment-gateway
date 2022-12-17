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
     * @param  \rkujawa\LaravelPaymentGateway\Contracts\Merchantable $merchant
     * @param  \rkujawa\LaravelPaymentGateway\Contracts\Providable $provider
     */
    public function __construct(Merchantable $merchant, Providable $provider)
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
