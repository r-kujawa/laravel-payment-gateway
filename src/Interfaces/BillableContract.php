<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

interface BillableContract
{
    /**
     * Get the billable's identifier.
     *
     * @return string
     */
    public function getBillableId();

    /**
     * Get the billable's email address.
     *
     * @return string
     */
    public function getBillableEmail();
}
