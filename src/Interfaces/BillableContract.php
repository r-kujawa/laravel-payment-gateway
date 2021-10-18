<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

interface BillableContract
{
    /**
     * Get the billable's identifier.
     *
     * @return string
     */
    public function getBillableKey();

    // TODO: Add methods like charge, refund, etc...
}
