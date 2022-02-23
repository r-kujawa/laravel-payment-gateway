<?php

namespace rkujawa\LaravelPaymentGateway\Contracts;

interface Billable
{
    /**
     * Get the billable's wallets.
     *
     * @return void
     */
    public function wallets();
}
