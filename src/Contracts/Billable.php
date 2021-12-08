<?php

namespace rkujawa\LaravelPaymentGateway\Contracts;

interface Billable
{
    /**
     * Get the billable's identifier.
     *
     * @return string
     */
    public function getBillableKey();

    // TODO: Add methods like charge, refund, etc...
}
