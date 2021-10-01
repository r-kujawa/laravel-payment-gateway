<?php

namespace rkujawa\LaravelPaymentGateway\DataTransferObjects;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use rkujawa\LaravelPaymentGateway\database\Factories\AddressDtoFactory;
use rkujawa\LaravelPaymentGateway\Helpers\Sanitizer;

class Address extends DataTransferObject
{
    use HasFactory;

    public static function newFactory()
    {
        return AddressDtoFactory::new();
    }

    protected function properties(): array
    {
        return [
            'country',
            'state',
            'city',
            'county',
            'street1',
            'street2',
            'zip'
        ];
    }

    public function setCountryProperty(string $country): void
    {
        $this->properties['country'] = Sanitizer::run(
            $country,
            [
                'trim',
                'tighten',
                'capitalize'
            ]
        );
    }

    public function setStateProperty(string $state): void
    {
        $this->properties['state'] = Sanitizer::run(
            $state,
            [
                'trim',
                'tighten',
                'capitalize'
            ]
        );
    }

    public function setCityProperty(string $city): void
    {
        $this->properties['city'] = Sanitizer::run(
            $city,
            [
                'trim',
                'tighten',
                'capitalize'
            ]
        );
    }

    public function setCountyProperty(string $county): void
    {
        $this->properties['county'] = Sanitizer::run(
            $county,
            [
                'trim',
                'tighten',
                'capitalize'
            ]
        );
    }

    public function setStreet1Property(string $street1): void
    {
        $this->properties['street1'] = Sanitizer::run(
            $street1,
            [
                'trim',
                'tighten',
                'capitalize'
            ]
        );
    }

    public function setStreet2Property(string $street2): void
    {
        $this->properties['street2'] = Sanitizer::run(
            $street2,
            [
                'trim',
                'tighten',
                'capitalize'
            ]
        );
    }

    public function setZipProperty(string $zip): void
    {
        $this->properties['zip'] = Sanitizer::run(
            $zip,
            [
                'trim',
                'tighten',
                'uppercase'
            ]
        );
    }
}
