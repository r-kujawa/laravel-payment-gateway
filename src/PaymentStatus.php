<?php

namespace rkujawa\LaravelPaymentGateway;

class PaymentStatus
{
    const AUTHORIZED = 200;
    const APPROVED = 201;
    const PARTIALLY_APPROVED = 202;
    const PENDING = 300;
    const PROCESSING_ASYNC = 301;
    const REFUSED = 400;
    const DECLINED = 401;
    const REFERRED = 402;
    const NOT_ENOUGH_BALANCE = 403;
    const AUTHORIZATION_REVOKED = 404;
    const INVALID_PAYMENT_METHOD = 410;
    const INVALID_CARD_NUMBER = 411;
    const EXPIRED_CARD = 412;
    const SECURITY_CODE_DECLINED = 413;
    const AVS_DECLINED = 414;
    const FRAUD = 420;
    const MERCHANT_FRAUD = 421;
    const ACQUIRER_FRAUD = 422;
    const ISSUER_FRAUD = 423;
    const INVALID_TRANSACTION = 430;
    const INVALID_AMOUNT = 431;
    const TRANSACTION_NOT_SUPPORTED = 432;
    const TRANSACTION_NOT_PERMITTED = 433;
    const PAYMENT_METHOD_ISSUE = 440;
    const BLOCKED_CARD = 441;
    const RESTRICTED_CARD = 442;
    const PROCESSING_ISSUE = 450;
    const ISSUER_UNAVAILABLE = 451;
    const ERROR = 500;
    const ACQUIRER_ERROR = 501;
    const ISSUER_ERROR = 502;
    const UNKNOWN_ERROR = 503;

    /**
     * List of all supported payment response codes.
     *
     * @var array
     */
    public static $codes = [
        self::AUTHORIZED => 'Authorized',
        self::APPROVED => 'Approved',
        self::PARTIALLY_APPROVED => 'Partially approved',
        self::PENDING => 'Pending',
        self::PROCESSING_ASYNC => 'Processing asynchronously',
        self::REFUSED => 'Refused',
        self::DECLINED => 'Declined',
        self::REFERRED => 'Referred',
        self::NOT_ENOUGH_BALANCE => 'Not enough balance',
        self::AUTHORIZATION_REVOKED => 'Authorization revoked',
        self::INVALID_PAYMENT_METHOD => 'Invalid payment information',
        self::INVALID_CARD_NUMBER => 'Invalid card number',
        self::EXPIRED_CARD => 'Expired card',
        self::SECURITY_CODE_DECLINED => 'Invalid security code',
        self::AVS_DECLINED => 'Invalid address information',
        self::FRAUD => 'Fraud',
        self::MERCHANT_FRAUD => 'Merchant suspected fraud',
        self::ACQUIRER_FRAUD => 'Acquirer suspected fraud',
        self::ISSUER_FRAUD => 'Issuer suspected fraud',
        self::INVALID_TRANSACTION => 'Invalid transaction',
        self::INVALID_AMOUNT => 'Invalid amount',
        self::TRANSACTION_NOT_SUPPORTED => 'Transaction not supported',
        self::TRANSACTION_NOT_PERMITTED => 'Transaction not permitted',
        self::PAYMENT_METHOD_ISSUE => 'Payment method issue',
        self::BLOCKED_CARD => 'Card blocked',
        self::RESTRICTED_CARD => 'Card restricted',
        self::PROCESSING_ISSUE => 'Processing issue',
        self::ISSUER_UNAVAILABLE => 'Issuer unavailable',
        self::ERROR => 'Error',
        self::ACQUIRER_ERROR => 'Acquirer Error',
        self::ISSUER_ERROR => 'Issuer Error',
        self::UNKNOWN_ERROR => 'Unknown Error',
    ];

    /**
     * List of all supported payment response code messages.
     *
     * @var array
     */
    public static $messages = [
        self::AUTHORIZED => 'The transaction was authorized.',
        self::APPROVED => 'The transaction was approved.',
        self::PARTIALLY_APPROVED => 'The transaction was partially approved.',
        self::PENDING => 'The transaction is pending.',
        self::PROCESSING_ASYNC => 'The transaction is being completed asynchronously.',
        self::REFUSED => 'The transaction was refused.',
        self::DECLINED => 'The transaction was declined.',
        self::REFERRED => 'The transaction must be authorized by the the cardholder.',
        self::NOT_ENOUGH_BALANCE => 'Not enough balance/funds for the transaction to be processed.',
        self::AUTHORIZATION_REVOKED => 'The authorization for the transaction was revoked.',
        self::INVALID_PAYMENT_METHOD => 'The payment method information is not valid.',
        self::INVALID_CARD_NUMBER => 'The card number is not valid.',
        self::EXPIRED_CARD => 'The card is expired.',
        self::SECURITY_CODE_DECLINED => 'The security code is not valid.',
        self::AVS_DECLINED => 'The address information is not valid.',
        self::FRAUD => 'The transaction was marked fraudulent.',
        self::MERCHANT_FRAUD => 'The transaction was marked fraudulent my the merchant.',
        self::ACQUIRER_FRAUD => 'The transaction was marked fraudulent by the acquirer.',
        self::ISSUER_FRAUD => 'The transaction was marked fraudulent by the issuer.',
        self::INVALID_TRANSACTION => 'The transaction is not valid.',
        self::INVALID_AMOUNT => 'The amount provided is not valid.',
        self::TRANSACTION_NOT_SUPPORTED => 'The transaction is not supported.',
        self::TRANSACTION_NOT_PERMITTED => 'The transaction is not permitted.',
        self::PAYMENT_METHOD_ISSUE => 'There is an existing payment issue for this payment method.',
        self::BLOCKED_CARD => 'The card is blocked.',
        self::RESTRICTED_CARD => 'The card is restricted',
        self::PROCESSING_ISSUE => 'There was a processing issue.',
        self::ISSUER_UNAVAILABLE => 'The issuer is not reachable.',
        self::ERROR => 'An unexpected error occurred.',
        self::ACQUIRER_ERROR => 'There was an error while the acquirer was attempting to process the transaction.',
        self::ISSUER_ERROR => 'There was an error while the issuer was attempting to process the transaction.',
        self::UNKNOWN_ERROR => 'An unknown error occurred.',
    ];

    /**
     * List of all supported payment response codes with their parent codes.
     *
     * @var array
     */
    public static $hierarchy = [
        self::APPROVED => self::AUTHORIZED,
        self::PARTIALLY_APPROVED => self::AUTHORIZED,
        self::PROCESSING_ASYNC => self::PENDING,
        self::DECLINED => self::REFUSED,
        self::REFERRED => self::REFUSED,
        self::NOT_ENOUGH_BALANCE => self::REFUSED,
        self::AUTHORIZATION_REVOKED => self::REFUSED,
        self::INVALID_PAYMENT_METHOD => self::REFUSED,
        self::INVALID_CARD_NUMBER => self::INVALID_PAYMENT_METHOD,
        self::EXPIRED_CARD => self::INVALID_PAYMENT_METHOD,
        self::SECURITY_CODE_DECLINED =>  self::INVALID_PAYMENT_METHOD,
        self::AVS_DECLINED => self::INVALID_PAYMENT_METHOD,
        self::FRAUD => self::REFUSED,
        self::MERCHANT_FRAUD => self::FRAUD,
        self::ACQUIRER_FRAUD => self::FRAUD,
        self::ISSUER_FRAUD => self::FRAUD,
        self::INVALID_TRANSACTION => self::REFUSED,
        self::INVALID_AMOUNT => self::INVALID_TRANSACTION,
        self::TRANSACTION_NOT_SUPPORTED => self::INVALID_TRANSACTION,
        self::TRANSACTION_NOT_PERMITTED => self::INVALID_TRANSACTION,
        self::PAYMENT_METHOD_ISSUE => self::REFUSED,
        self::BLOCKED_CARD => self::PAYMENT_METHOD_ISSUE,
        self::RESTRICTED_CARD => self::PAYMENT_METHOD_ISSUE,
        self::PROCESSING_ISSUE => self::REFUSED,
        self::ISSUER_UNAVAILABLE => self::PROCESSING_ISSUE,
        self::ACQUIRER_ERROR => self::ERROR,
        self::ISSUER_ERROR => self::ERROR,
        self::UNKNOWN_ERROR => self::ERROR,
    ];

    /**
     * Get the definition of the provided code.
     *
     * @param int $status
     * @return string|null
     */
    public static function get($status)
    {
        return self::$codes[$status] ?? null;
    }

    /**
     * Get the description of the provided code.
     *
     * @param int $status
     * @return string|null
     */
    public static function getMessage($status)
    {
        return self::$messages[$status] ?? null;
    }

    /**
     * Get the top level code from the code hierarchy.
     *
     * @param int $status
     * @return int
     */
    public static function getParent($status)
    {
        while (array_key_exists($status, self::$hierarchy) && $status !== self::$hierarchy[$status]) {
            $status = self::$hierarchy[$status];
        }

        return $status;
    }
}
