<?php

namespace rkujawa\LaravelPaymentGateway\Providers\Fake;


use rkujawa\LaravelPaymentGateway\Contracts\GatewayRequest;
use rkujawa\LaravelPaymentGateway\Contracts\GatewayResponse;
use rkujawa\LaravelPaymentGateway\Models\PaymentMethod;
use rkujawa\LaravelPaymentGateway\PaymentGateway;
use rkujawa\LaravelPaymentGateway\RawPayment;

final class FakeGateway extends PaymentGateway implements GatewayRequest
{
    public function __construct(string $fakeProvider)
    {
        $this->attributes['provider'] = $fakeProvider;

        $this->isFake = true;
    }

    protected function merchantRequest(array $args)
    {
        return null;
    }

    public function createCustomerProfile(Client $client): GatewayResponse
    {
        // TODO: Implement createCustomerProfile() method.
    }

    public function createPaymentProfile(int $customerToken, RawPayment $paymentMethod): GatewayResponse
    {
        // TODO: Implement createPaymentProfile() method.
    }

    public function chargeProfile(PaymentMethod $paymentMethod, int $amount, string $description, int $ordernum): GatewayResponse
    {
        // TODO: Implement chargeProfile() method.
    }

    public function chargeCard(RawPayment $payWith, int $amount, string $description, int $ordernum): GatewayResponse
    {
        // TODO: Implement chargeCard() method.
    }

    public function void(): GatewayResponse
    {
        // TODO: Implement void() method.
    }

    public function refund(): GatewayResponse
    {
        // TODO: Implement refund() method.
    }
}
