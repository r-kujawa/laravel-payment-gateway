<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

interface GatewayRequestContract
{
    /**
     * @param BillableContract $client
     * @return GatewayResponseContract
     */
    public function createCustomerProfile(BillableContract $client): GatewayResponseContract;

    /**
     * @param string $customerToken
     * @return GatewayResponseContract
     */
    public function deleteCustomerProfile(string $customerToken): GatewayResponseContract;

    /**
     * @param string $customerToken
     * @return GatewayResponseContract
     */
    public function getCustomerProfile(string $customerToken): GatewayResponseContract;

    /**
     * @param string $clientId
     * @param string $customerToken
     * @param string $email
     * @param string $description
     * @return GatewayResponseContract
     */
    public function updateCustomerProfile(
        string $clientId,
        string $customerToken,
        string $email,
        string $description
    ): GatewayResponseContract;

    /**
     * @param int $paymentCustomerId
     * @param PaymentTypeContract $paymentMethod
     * @return GatewayResponseContract
     */
    public function createPaymentMethod(int $paymentCustomerId, PaymentTypeContract $paymentMethod): GatewayResponseContract;

    /**
     * @param string $customerToken
     * @param string $paymentToken
     * @return GatewayResponseContract
     */
    public function deletePaymentMethod(string $customerToken, string $paymentToken): GatewayResponseContract;

    /**
     * @param string $customerToken
     * @param string $paymentToken
     * @return GatewayResponseContract
     */
    public function getPaymentMethod(string $customerToken, string $paymentToken): GatewayResponseContract;

    /**
     * @param string $customerToken
     * @param string $paymentToken
     * @param PaymentTypeContract $paymentType
     * @return GatewayResponseContract
     */
    public function updatePaymentMethod(
        string $customerToken,
        string $paymentToken,
        PaymentTypeContract $paymentType
    ): GatewayResponseContract;

    /**
     * @param PaymentTypeContract $payment
     * @param int $amount
     * @param string $description
     * @return GatewayResponseContract
     */
    public function charge(PaymentTypeContract $payment, int $amount, string $description): GatewayResponseContract;

    /**
     * @return GatewayResponseContract
     */
    public function void(): GatewayResponseContract;

    /**
     * @return GatewayResponseContract
     */
    public function refund(): GatewayResponseContract;
}
