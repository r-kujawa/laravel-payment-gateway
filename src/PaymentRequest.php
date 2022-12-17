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
     * The Payment provider.
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
     * @param  \rkujawa\LaravelPaymentGateway\Contracts\Merchantable|null $merchant
     * @param  \rkujawa\LaravelPaymentGateway\Contracts\Providable|null $provider
     */
    public function __construct(Merchantable $merchant = null, Providable $provider = null)
    {
        // TODO: Can this really be null?
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
