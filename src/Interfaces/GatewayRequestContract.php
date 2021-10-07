<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

interface GatewayRequestContract
{
    public function createCustomerProfile(BillableContract $client): GatewayResponse;
    public function deleteCustomerProfile(string $customerToken): GatewayResponse;
    public function getCustomerProfile(string $customerToken): GatewayResponse;

    public function updateCustomerProfile(
        string $clientId,
        string $customerToken,
        string $email,
        string $description
    ): GatewayResponse;

    public function createPaymentMethod(int $paymentCustomerId, PaymentTypeContract $paymentMethod): GatewayResponse;
    public function deletePaymentMethod(string $customerToken, string $paymentToken): GatewayResponse;
    public function getPaymentMethod(string $customerToken, string $paymentToken): GatewayResponse;

    public function updatePaymentMethod(
        string $customerToken,
        string $paymentToken,
        PaymentTypeContract $paymentType
    ): GatewayResponse;

    public function charge(PaymentTypeContract $payment, int $amount, string $description): GatewayResponse;
    public function void(): GatewayResponse;
    public function refund(): GatewayResponse;
}
