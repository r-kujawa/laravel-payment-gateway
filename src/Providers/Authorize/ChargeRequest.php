<?php

namespace rkujawa\LaravelPaymentGateway\Providers\Authorize;

trait ChargeRequest
{
    private function processCharge(array $transactionData)
    {
        // sanitize parameters
        $this->sanitize('createTransactionRequest', $transactionData);

        $response = $this->getClient()->createTransactionRequest($transactionData);

        $response = new AuthorizeResponse($response);
        $response->status = $response->isApproved();
        $response->transId = $response->isApproved() ? $response->getTransactionId() : null;
        $response->errorMessage = ($response->isDeclined() || $response->isError()) ? $response->getErrorMessage() : '';
        //check this line, need to check if not is an error.
        $response->avsCode = optional($response->objResponse())->avsResultCode ?? null;

        return $response;
    }
    
    private function makeChargeNoProfileArguments(array $billTo, int $amount, string $description, int $ordernum): array
    {
        $payment['creditCard'] = [
            'cardNumber' => str_replace(' ', '', $billTo['details']['number']),
            'expirationDate' => $billTo['expirationDate'], //'122016',
            'cardCode' => $billTo['details']['code'],
        ];

        $args = [
            'refId' => random_int(1000000, 100000000),
            'transactionRequest' => [
                'transactionType' => 'authCaptureTransaction',
                'amount' => $amount,
                'payment' => $payment,
                'order' => [
                    'invoiceNumber' => $ordernum,
                    'description' => $description,
                ],
            ],
        ];

        $args['transactionRequest']['customer'] = [
            'email' => $billTo['email'] ?? '',
        ];

        $args['transactionRequest']['billTo'] = [
            'firstName' => $billTo['contact']['firstName'],
            'lastName' => $billTo['contact']['lastName'],
            'company' => $billTo['contact']['company'] ?? '',
            'address' => $billTo['address']['street1'] . ' '. $billTo['address']['street2'],
            'city' => $billTo['address']['city'],
            'state' => $billTo['address']['state'],
            'zip' => $billTo['address']['zip'],
            'country' => $billTo['address']['country'] ?? 'USA',
            'phoneNumber' => $billTo['contact']['phone'] ?? '000-000-0000',
        ];

        $args['transactionRequest']['transactionSettings'] = [
            'setting' => [
                'settingName' => 'emailCustomer',
                'settingValue' => 0,
            ],
        ];

        return $args;
    }

    private function makeChargeArguments(array $tokens, int $amount, string $description, int $ordernum): array
    {
        $args = [
            'refId' => random_int(1000000, 100000000),
            'transactionRequest' => [
                'transactionType' => 'authCaptureTransaction',
                'amount' => $amount,
            ],
        ];

        $args['transactionRequest']['profile'] = [
            'customerProfileId' => $tokens['customerToken'],
            'paymentProfile' => [
                'paymentProfileId' => $tokens['paymentToken'],
            ],
        ];

        $args['transactionRequest']['order'] = [
            'invoiceNumber' => $ordernum,
            'description' => $description,
        ];
        // restrict transaction emails
        $args['transactionRequest']['transactionSettings'] = [
            'setting' => [
                'settingName' => 'emailCustomer',
                'settingValue' => 0,
            ],
        ];

        return $args;
    }
}
