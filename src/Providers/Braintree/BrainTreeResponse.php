<?php

namespace rkujawa\LaravelPaymentGateway\Providers\Braintree;

use rkujawa\LaravelPaymentGateway\Contracts\GatewayResponse;

class BraintreeResponse implements GatewayResponse
{
    private $response;

    public $status;
    public $transId;
    public $avsCode;
    public $errorMessage;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function isSuccessful(): bool
    {
        // TODO: Implement isSuccessful() method.
    }

    public function getRawResponse(): string
    {
        // TODO: Implement getRawResponse() method.
    }

    public function getErrorMessage(): string
    {
        // TODO: Implement getErrorMessage() method.
    }

    public function getErrorCode(): int
    {
        // TODO: Implement getErrorCode() method.
    }

    public function isApproved(): bool
    {
        // TODO: Implement isApproved() method.
    }

    public function isDuplicateProfile(): bool
    {
        // TODO: Implement isDuplicateProfile() method.
    }

    public function getTransactionId()
    {
        // TODO: Implement getTransactionId() method.
    }

    public function getAvsCode(): string
    {
        // TODO: Implement getAvsCode() method.
    }
}
