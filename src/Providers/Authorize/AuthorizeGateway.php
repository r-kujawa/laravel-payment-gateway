<?php

namespace rkujawa\LaravelPaymentGateway\Providers\Authorize;

use Authnetjson\AuthnetApiFactory;
use Authnetjson\AuthnetJsonRequest;
use rkujawa\LaravelPaymentGateway\Contracts\Buyer;
use rkujawa\LaravelPaymentGateway\Contracts\GatewayRequest;
use rkujawa\LaravelPaymentGateway\Contracts\GatewayResponse;
use rkujawa\LaravelPaymentGateway\Contracts\PaymentType;
use rkujawa\LaravelPaymentGateway\Helpers\Sanitizer;
use rkujawa\LaravelPaymentGateway\Models\PaymentCustomer;
use rkujawa\LaravelPaymentGateway\Models\PaymentMethod;
use rkujawa\LaravelPaymentGateway\PaymentGateway;

final class AuthorizeGateway extends PaymentGateway implements GatewayRequest
{
    use ChargeRequest;

    protected function merchantRequest(array $args): AuthnetJsonRequest
    {
        return AuthnetApiFactory::getJsonApiHandler(
            $args['id'],
            $args['secret'],
            $args['server']
        );
    }

    public function createCustomerProfile(Buyer $client): GatewayResponse
    {
        $params = [
            'profile' => [
                'merchantCustomerId' => Sanitizer::shorten($client->getId(), 20),
                'description' => Sanitizer::tighten($client->getFullName()),
                'email' => Sanitizer::run($client->getEmail(), ['compress', 'shorten' => [255]]),
            ],
        ];

        $response = new AuthorizeResponse($this->getClient()->createCustomerProfileRequest($params));

        if ($response->isSuccessful() || $response->isDuplicateProfile()) {
            $customerData['token'] = $response->getCustomerProfileId();
            //$customerData['client_id'] = $client->getId();
            $customerData['provider_id'] = $this->getProviderId();
            $this->storePaymentCustomer($customerData);
        }
        dump($response->getRawResponse());
        //log response
        return $response;
    }

    public function createPaymentMethod(int $customerToken, PaymentType $paymentMethod, ?int $transnum = null): GatewayResponse
    {
        $params = [
            'customerProfileId' => $customerToken,
            'paymentProfile' => [
                'customerType' => 'individual',
                'billTo' => $this->buildBillToParams($paymentMethod),
                'payment' => [
                    'creditCard' => [
                        'cardNumber' => str_replace(' ', '', $paymentMethod->details->number),
                        'expirationDate' => $paymentMethod->details->getExpirationDate('-'),
                        'cardCode' => ''.$paymentMethod->details->code .'',
                    ],
                ],
                'defaultPaymentProfile' => false,
            ],
            'validationMode' => 'testMode',
        ];

        $response = $this->getClient()->createCustomerPaymentProfileRequest($params);

        $response = new AuthorizeResponse($response);
        //log response
        if ($response->isSuccessful() || $response->isDuplicateProfile()) {
            $paymentData = [
                'token' => $response->getPaymentProfileId(),
                'payment_customer_id' => PaymentCustomer::findByToken($customerToken)->id,
                'first_name' => $paymentMethod->contact->firstName,
                'last_name' => $paymentMethod->contact->lastName,
                'last_digits' => $paymentMethod->details->last4Digits,
                'exp_month' => $paymentMethod->details->expMonth,
                'exp_year' => $paymentMethod->details->expYear,
                'type' => $paymentMethod->details->type,
            ];

            $this->storePaymentMethod($paymentData, $transnum);
        }

        return $response;
    }

    public function chargeCard(PaymentType $card, int $amount, string $description, int $ordernum): GatewayResponse
    {
        throw_unless($card, new \Exception('Card not provided'));
        $cardData = $card->toArray();

        $cardData['expirationDate'] = $card->details->getExpirationDate();
        $transactionData = $this->makeChargeNoProfileArguments($cardData, $amount, $description, $ordernum);

        return $this->processCharge($transactionData);
    }

    public function chargeToken(PaymentMethod $paymentMethod, int $amount, string $description, int $ordernum): GatewayResponse
    {
        $data = $this->makeChargeArguments($paymentMethod->getTokens(), $amount, $description, $ordernum);
        return $this->processCharge($data);
    }

    public function getCustomerProfile(string $customerToken): GatewayResponse
    {
        $attributes = ['customerProfileId' => $customerToken];
        $attributes['unmaskExpirationDate'] = true;
        $response = $this->getClient()->getCustomerProfileRequest($attributes);

        return new AuthorizeResponse($response);
    }

    public function getPaymentMethod(string $customerToken, string $paymentToken): GatewayResponse
    {
        $response = $this->getClient()->getCustomerPaymentProfileRequest([
            'customerProfileId' => $customerToken,
            'customerPaymentProfileId' => $paymentToken,
            'unmaskExpirationDate' => true,
        ]);

        return new AuthorizeResponse($response);
    }

    public function deleteCustomerProfile(string $customerToken): GatewayResponse
    {
        $response = $this->getClient()->deleteCustomerProfileRequest([
            'customerProfileId' => $customerToken,
        ]);

        return new AuthorizeResponse($response);
    }

    public function deletePaymentMethod(string $customerToken, string $paymentToken): GatewayResponse
    {
        $response = $this->getClient()->deleteCustomerPaymentProfileRequest([
            'customerProfileId' => $customerToken,
            'customerPaymentProfileId' => $paymentToken
        ]);

        return new AuthorizeResponse($response);
    }

    public function updateCustomerProfile(string $clientId, string $customerToken, string $email, string $description): GatewayResponse
    {
        $params = [
            'profile' => [
                'merchantCustomerId' => $clientId,
                'description' => $description,//max length 255
                'email' => $email,//max length 255
                'customerProfileId' => $customerToken,
            ],
        ];

        $response = $this->getClient()->updateCustomerProfileRequest($params);

        return new AuthorizeResponse($response);
    }

    public function updatePaymentMethod(string $customerToken, string $paymentToken, PaymentType $paymentType): GatewayResponse
    {
        $params = [
            'customerProfileId' => $customerToken,
            'paymentProfile' => [
                'customerType' => 'individual',
                'billTo' => $this->buildBillToParams($paymentType),
                'payment' => [
                    'creditCard' => [
                        'cardNumber' => str_replace(' ', '', $paymentType->details->number),
                        'expirationDate' => $paymentType->details->getExpirationDate('-'),
                        'cardCode' => ''.$paymentType->details->code .'',
                    ],
                ],
                'defaultPaymentProfile' => false,
                'customerPaymentProfileId' => $paymentToken
            ],
            'validationMode' => 'testMode',
        ];
        //check $params sanitization
        $response = $this->getClient()->updateCustomerPaymentProfileRequest($params);

        return new AuthorizeResponse($response);
    }

    private function buildBillToParams(PaymentType $paymentType): array
    {
        return [
            'firstName' => $paymentType->contact->firstName,
            'lastName' => $paymentType->contact->lastName,
            'company' => $paymentType->contact->company ?? '',
            'address' => $paymentType->address->street1 . ' ' . $paymentType->address->street2,
            'city' => $paymentType->address->city,
            'state' => $paymentType->address->state,
            'zip' => $paymentType->address->zip,
            'country' => $paymentType->address->country ?? 'USA',
            'phoneNumber' => $paymentType->contact->phone ?? '000-000-0000',
        ];
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
