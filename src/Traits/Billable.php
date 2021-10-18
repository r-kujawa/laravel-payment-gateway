<?php

namespace rkujawa\LaravelPaymentGateway\Traits;

use rkujawa\LaravelPaymentGateway\Models\Wallet;

trait Billable
{
    public function getBillableKey()
    {
        return 'id';
    }

    public function wallets()
    {
        return $this->morphMany(Wallet::class, 'billable', 'billable_type', 'billable_id', $this->getBillableKey());
    }
}