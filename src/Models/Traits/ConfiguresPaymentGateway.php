<?php

namespace rkujawa\LaravelPaymentGateway\Models\Traits;

use rkujawa\LaravelPaymentGateway\PaymentGateway;

trait ConfiguresPaymentGateway
{
    /**
     * The payment method's pre-configured gateway.
     *
     * @var \rkujawa\LaravelPaymentGateway\PaymentGateway
     */
    private $paymentGateway;

    /**
     * Retrieve the payment method's configured gateway.
     *
     * @return \rkujawa\LaravelPaymentGateway\PaymentGateway
     */
    public function getGatewayAttribute()
    {
        if (! isset($this->paymentGateway)) {
            $this->paymentGateway = (new PaymentGateway)
                ->provider($this->provider)
                ->merchant($this->merchant);
        }

        return $this->paymentGateway;
    }
}
