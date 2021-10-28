<?php

namespace rkujawa\LaravelPaymentGateway\Traits;

trait AmountConverter
{
    public function getAmountAttribute()
    {
        return ($this->attributes['amount_cents'] ?? 0) / 100;
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount_cents'] = $value * 100;
    }
}
