<?php

namespace rkujawa\LaravelPaymentGateway;

use rkujawa\LaravelPaymentGateway\Contracts\Buyer;
use rkujawa\LaravelPaymentGateway\Contracts\GatewayRequest;
use rkujawa\LaravelPaymentGateway\Contracts\GatewayResponse;
use rkujawa\LaravelPaymentGateway\Contracts\PaymentType;
use rkujawa\LaravelPaymentGateway\Models\PaymentCustomer;
use rkujawa\LaravelPaymentGateway\Models\PaymentMethod;

/**
 * Class PaymentGatewayService
 * @package App\Payments
 *
 * This class will be injected at Controller level.
 */
class PaymentGatewayService
{
    private $paymentGateway;

    public function __construct(GatewayRequest $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function getProvider()
    {
        return $this->paymentGateway->provider;
    }

    public function setProvider(string $provider, bool $useSandbox = false): void
    {
        $this->paymentGateway = PaymentGatewayFactory::create($provider, $useSandbox);
    }

    public function getMerchant()
    {
        return $this->paymentGateway->merchant;
    }

    public function setMerchant(string $merchant): void
    {
        $this->paymentGateway->merchant = (strtolower($merchant));
    }

    public function charge(PaymentType $payment, int $amount, string $description, $ordernum): GatewayResponse
    {
        return $this->paymentGateway->charge($payment, $amount, $description, $ordernum);
    }

    public function createPaymentCustomer(Buyer $client)
    {
        return $this->paymentGateway->createCustomerProfile($client);
    }

    public function createPaymentMethod(...$args)
    {
        if ($this->getProvider() === 'authorize') {
            @list($customerToken, $cardData, $transnum) = $args;
            return $this->paymentGateway->createPaymentMethod($customerToken, $cardData, $transnum);
        }
        return $this->paymentGateway->createPaymentProfile();
    }

    public function void(string $transactionId)
    {
        $this->paymentGateway->void($transactionId);
    }

    public function getPaymentMethod(string $customerToken, string $paymentToken)
    {
        return $this->paymentGateway->getPaymentMethod($customerToken, $paymentToken);
    }

    public function updatePaymentMethod(string $customerToken, string $paymentToken, PaymentType $paymentType)
    {
        return $this->paymentGateway->updatePaymentMethod($customerToken, $paymentToken, $paymentType);
    }

    public function getCustomerProfile(string $customerToken)
    {
        return $this->paymentGateway->getCustomerProfile($customerToken);
    }

    public function deleteCustomerProfile(string $customerToken)
    {
        $response = $this->paymentGateway->deleteCustomerProfile($customerToken);

        if ($response->isSuccessful()) {
            PaymentCustomer::where('token', $customerToken)->delete();
        }

        return $response;
    }

    public function deletePaymentMethod(string $customerToken, string $paymentToken)
    {
        $response = $this->paymentGateway->deletePaymentMethod($customerToken, $paymentToken);
        if ($response->isSuccessful()) {
            PaymentMethod::where('token', $paymentToken)->delete();
        }

        return $response;
    }

    public function updateCustomerProfile(string $clientId, string $customerToken, string $email, string $description)
    {
        return $this->paymentGateway->updateCustomerProfile($clientId, $customerToken, $email, $description);
    }

    public function isFake()
    {
        return $this->paymentGateway->isFake;
    }
}
