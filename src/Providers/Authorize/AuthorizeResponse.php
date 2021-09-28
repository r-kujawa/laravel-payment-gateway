<?php

namespace rkujawa\LaravelPaymentGateway\Providers\Authorize;

use Authnetjson\AuthnetJsonResponse;
use rkujawa\LaravelPaymentGateway\Contracts\GatewayResponse;

class AuthorizeResponse implements GatewayResponse
{
    public const AUTHORIZE_DUPLICATE_RECORD_ERROR_CODE = 'E00039';

    private $response;

    public $status;
    public $transId;
    public $avsCode;
    public $errorMessage;

    public function __construct(AuthnetJsonResponse $response)
    {
        $this->response = $response;
    }

    public function isSuccessful(): bool
    {
        return $this->response->isSuccessful();
    }

    public function isError()
    {
        return $this->response->isError();
    }

    public function getRawResponse(): string
    {
        return $this->response->getRawResponse();
    }

    public function getErrorMessage(): string
    {
        return $this->response->getErrorMessage();
    }

    public function getErrorCode(): int
    {
        return $this->response->getErrorCode();
    }

    public function getTransactionId()
    {
        return $this->transId;
    }

    public function isApproved(): bool
    {
        return $this->response->isApproved();
    }

    public function isDuplicateProfile(): bool
    {
        return
            method_exists($this->response, 'isError') &&
            $this->response->isError() &&
            $this->response->getErrorCode() === self::AUTHORIZE_DUPLICATE_RECORD_ERROR_CODE;
    }

    public function getCustomerProfileId()
    {
        if ($this->isDuplicateProfile()) {
            preg_match_all('!\d+!', $this->getErrorMessage(), $matches);
            return $matches[0][0];
        }

        return $this->response->customerProfileId;
    }

    public function getPaymentProfileId()
    {
        return $this->response->customerPaymentProfileId;
    }

    public function getAvsCode(): string
    {
        return $this->avsCode;
    }

    public function objResponse()
    {
        return $this->response;
    }

    public function isDeclined(): bool
    {
        return $this->response->isDeclined();
    }
}
