<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

/**
 * To discuss: Maybe this contract should be smaller and only have duty to comunicate errors and status of transaction
 * (successful or not)
 */
interface GatewayResponseContract
{
    /**
     * @return bool
     */
    public function isSuccessful(): bool;

    /**
     * @return bool
     */
    public function isApproved(): bool;

    /**
     * @return string
     */
    public function getRawResponse(): string;

    /**
     * @return string
     */
    public function getErrorMessage(): string;

    /**
     * @return int
     */
    public function getErrorCode(): int;

    /**
     * @return bool
     */
    public function isDuplicateProfile(): bool;

    /**
     * @return mixed
     */
    public function getTransactionId();

    /**
     * @return string
     */
    public function getAvsCode(): string;
}
