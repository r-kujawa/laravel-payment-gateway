<?php

namespace rkujawa\LaravelPaymentGateway\Providers\Braintree;

use rkujawa\LaravelPaymentGateway\Contracts\Buyer;
use rkujawa\LaravelPaymentGateway\Contracts\GatewayRequest;
use rkujawa\LaravelPaymentGateway\Contracts\GatewayResponse;
use rkujawa\LaravelPaymentGateway\Contracts\PaymentType;
use rkujawa\LaravelPaymentGateway\Models\PaymentMethod;
use rkujawa\LaravelPaymentGateway\PaymentGateway;

class BrainTreeGateway extends PaymentGateway implements GatewayRequest
{
    public function merchantRequest(array $args)
    {
        // TODO: Implement merchantRequest() method.
    }

    public function createCustomerProfile(Buyer $client): GatewayResponse
    {
        // TODO: Implement createCustomerProfile() method.
    }

    public function createPaymentMethod(int $paymentCustomerId, PaymentType $paymentMethod, ?int $transnum = null): GatewayResponse
    {
        // TODO: Implement createPaymentProfile() method.
    }

    public function chargeProfile(PaymentMethod $paymentMethod, int $amount, string $description, int $ordernum): GatewayResponse
    {
        // TODO: Implement chargeProfile() method.
    }

    public function chargeCard(PaymentType $payWith, int $amount, string $description): GatewayResponse
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

    public function deleteCustomerProfile(string $customerToken): GatewayResponse
    {
        // TODO: Implement deleteCustomerProfile() method.
    }

    public function getCustomerProfile(string $customerToken): GatewayResponse
    {
        // TODO: Implement getCustomerProfile() method.
    }

    public function updateCustomerProfile(string $clientId, string $customerToken, string $email, string $description): GatewayResponse
    {
        // TODO: Implement updateCustomerProfile() method.
    }

    public function deletePaymentMethod(string $customerToken, string $paymentToken): GatewayResponse
    {
        // TODO: Implement deletePaymentMethod() method.
    }

    public function getPaymentMethod(string $customerToken, string $paymentToken): GatewayResponse
    {
        // TODO: Implement getPaymentMethod() method.
    }

    public function updatePaymentMethod(string $customerToken, string $paymentToken, PaymentType $paymentType): GatewayResponse
    {
        // TODO: Implement updatePaymentMethod() method.
    }
}
