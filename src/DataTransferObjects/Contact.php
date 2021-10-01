<?php

namespace rkujawa\LaravelPaymentGateway\DataTransferObjects;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use rkujawa\LaravelPaymentGateway\database\Factories\ContactDtoFactory;
use rkujawa\LaravelPaymentGateway\Helpers\Sanitizer;

class Contact extends DataTransferObject
{
    use HasFactory;

    public static function newFactory()
    {
        return ContactDtoFactory::new();
    }

    protected function properties(): array
    {
        return [
            'firstName',
            'lastName',
            'company',
            'email',
            'phone',
        ];
    }

    protected function setFirstNameProperty($firstName): void
    {
        $this->properties['firstName'] = Sanitizer::run(
            $firstName,
            [
                'trim',
                'tighten',
                'capitalize'
            ]
        );
    }

    protected function setLastNameProperty($lastName): void
    {
        $this->properties['lastName'] = Sanitizer::run(
            $lastName,
            [
                'trim',
                'tighten',
                'capitalize'
            ]
        );
    }

    protected function setCompanyProperty($company): void
    {
        $this->properties['company'] = Sanitizer::run(
            $company,
            [
                'trim',
                'tighten',
                'capitalize'
            ]
        );
    }

    protected function setEmailProperty($email): void
    {
        $this->properties['email'] = Sanitizer::run(
            $email,
            [
                'lowercase',
                'compress'
            ]
        );
    }

    protected function setPhoneProperty($phone): void
    {
        $this->properties['phone'] = Sanitizer::run(
            $phone,
            [
                'numerify',
                'compress'
            ]
        );
    }
}
