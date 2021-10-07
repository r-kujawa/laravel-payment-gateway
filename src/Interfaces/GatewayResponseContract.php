<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

interface GatewayResponseContract
{
    public function isSuccessful(): bool;

    public function isApproved(): bool;

    public function getRawResponse(): string;

    public function getErrorMessage(): string;

    public function getErrorCode(): int;

    public function isDuplicateProfile(): bool;

    public function getTransactionId();

    public function getAvsCode(): string;
}