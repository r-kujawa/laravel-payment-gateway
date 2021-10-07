<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

interface GatewayRequestContract
{
    public function createCustomerProfile(BillableContract $client): GatewayResponseContract;
    public function deleteCustomerProfile(string $customerToken): GatewayResponseContract;
    public function getCustomerProfile(string $customerToken): GatewayResponseContract;

    public function updateCustomerProfile(
        string $clientId,
        string $customerToken,
        string $email,
        string $description
    ): GatewayResponseContract;

    public function createPaymentMethod(int $paymentCustomerId, PaymentTypeContract $paymentMethod): GatewayResponseContract;
    public function deletePaymentMethod(string $customerToken, string $paymentToken): GatewayResponseContract;
    public function getPaymentMethod(string $customerToken, string $paymentToken): GatewayResponseContract;

    public function updatePaymentMethod(
        string $customerToken,
        string $paymentToken,
        PaymentTypeContract $paymentType
    ): GatewayResponseContract;

    public function charge(PaymentTypeContract $payment, int $amount, string $description): GatewayResponseContract;
    public function void(): GatewayResponseContract;
    public function refund(): GatewayResponseContract;
}
