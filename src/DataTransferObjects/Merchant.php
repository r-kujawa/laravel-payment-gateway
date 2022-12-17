<?php

namespace rkujawa\LaravelPaymentGateway\DataTransferObjects;

use Illuminate\Support\Collection;
use rkujawa\LaravelPaymentGateway\Contracts\Merchantable;
use rkujawa\LaravelPaymentGateway\Traits\SimulateAttributes;

class Merchant implements Merchantable
{
    use SimulateAttributes;

    /**
     * Collection of providers this merchant is supported by.
     *
     * @var \Illuminate\Support\Collection
     */
    public $providers;

    public function __construct(array $data)
    {
        $this->attributes = $data;

        $this->providers = (new Collection($data['providers'] ?? []))->map(function ($provider, $key) {
            if (is_array($provider)) {
                return array_merge(
                    config('payment.providers.' . $key),
                    $provider
                );
            }

            return config('payment.providers.' . $provider);
        });
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
     * Get the provider's slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->attributes['slug'];
    }
}
