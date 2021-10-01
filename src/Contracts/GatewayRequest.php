<?php

namespace rkujawa\LaravelPaymentGateway\Contracts;


interface GatewayRequest
{
    public function createCustomerProfile(Buyer $client): GatewayResponse;
    public function deleteCustomerProfile(string $customerToken): GatewayResponse;
    public function getCustomerProfile(string $customerToken): GatewayResponse;

    public function updateCustomerProfile(
        string $clientId,
        string $customerToken,
        string $email,
        string $description
    ): GatewayResponse;

    public function createPaymentMethod(int $paymentCustomerId, PaymentType $paymentMethod): GatewayResponse;
    public function deletePaymentMethod(string $customerToken, string $paymentToken): GatewayResponse;
    public function getPaymentMethod(string $customerToken, string $paymentToken): GatewayResponse;

    public function updatePaymentMethod(
        string $customerToken,
        string $paymentToken,
        PaymentType $paymentType
    ): GatewayResponse;

    public function charge(PaymentType $payment, int $amount, string $description): GatewayResponse;
    public function void(): GatewayResponse;
    public function refund(): GatewayResponse;
}
