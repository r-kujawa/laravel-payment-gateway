<?php

namespace rkujawa\LaravelPaymentGateway\Traits;

trait AmountConverter
{
    public function getAmountAttribute()
    {
        return $this->amount_cents / 100; //this can be determined by config depending on a currency
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount_cents'] = $value * 100;
    }
}