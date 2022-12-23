<?php

namespace rkujawa\LaravelPaymentGateway\Contracts;

interface Merchantable
{
    /**
     * Get the merchant's id.
     *
     * @return int
     */
    public function getId();

    /**
     * Get the merchant's name.
     *
     * @return string
     */
    public function getName();
}
