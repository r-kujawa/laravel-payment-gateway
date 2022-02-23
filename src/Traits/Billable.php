<?php

namespace rkujawa\LaravelPaymentGateway\Traits;

use rkujawa\LaravelPaymentGateway\Models\Wallet;

trait Billable
{
    /**
     * Get the billable's wallets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function wallets()
    {
        return $this->morphMany(Wallet::class, 'billable');
    }
}
