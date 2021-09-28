<?php

namespace rkujawa\LaravelPaymentGateway\Types;

use rkujawa\LaravelPaymentGateway\Contracts\PaymentType;
use rkujawa\LaravelPaymentGateway\DataTransferObjects\Address;
use rkujawa\LaravelPaymentGateway\DataTransferObjects\Card;
use rkujawa\LaravelPaymentGateway\DataTransferObjects\Contact;
use Illuminate\Contracts\Support\Arrayable;

class CardPayment implements PaymentType, Arrayable
{
    public $details;
    public $contact;
    public $address;

    public function __construct(Card $card, Contact $contact, Address $address)
    {
        $this->details = $card;
        $this->contact = $contact;
        $this->address = $address;
    }

    public function getPaymentType(): string
    {
        return PaymentType::CARD;
    }

    public function toArray(): array
    {
        return [
            'details' => $this->details->toArray(),
            'contact' => $this->contact->toArray(),
            'address' => $this->address->toArray()
        ];
    }
}
