<?php

namespace rkujawa\LaravelPaymentGateway\DataTransferObjects;

use rkujawa\LaravelPaymentGateway\Contracts\Providable;
use rkujawa\LaravelPaymentGateway\Traits\SimulateAttributes;

class Provider implements Providable
{
    use SimulateAttributes;

    public function __construct(array $data)
    {
        $this->attributes = $data;
    }

    /**
     * Get the provider's id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->attributes['id'];
    }

    /**
     * Get the provider's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->attributes['name'];
    }
}
