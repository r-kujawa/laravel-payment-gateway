<?php

namespace rkujawa\LaravelPaymentGateway\Models\Traits;

trait WalletRequests
{
    use ConfiguresPaymentGateway;

    /**
     * Request the wallet details from the provider.
     *
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentResponse
     */
    public function requestDetails()
    {
        $this->gateway->getWallet($this);
    }
}
