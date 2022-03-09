<?php

namespace rkujawa\LaravelPaymentGateway\Models\Traits;

trait WalletRequests
{
    use ConfiguresPaymentGateway;

    /**
     * Fetch the wallet details from the provider.
     *
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function fetch()
    {
        return $this->gateway->getWallet($this);
    }
}
