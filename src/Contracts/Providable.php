<?php

namespace rkujawa\LaravelPaymentGateway\Contracts;

interface Providable
{
    /**
     * Get the provider's id.
     *
     * @return int
     */
    public function getId();

    /**
     * Get the provider's name.
     */
    public function getName();
}
