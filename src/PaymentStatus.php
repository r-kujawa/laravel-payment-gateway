<?php

namespace rkujawa\LaravelPaymentGateway;

class PaymentStatus
{
    const AUTHORIZED = 200;
    const CAPTURED = 201;
    const PARTIALLY_CAPTURED = 202;
    const SETTLED = 203;
    const VOIDED = 210;
    const REFUNDED = 211;
    const PARTIALLY_REFUNDED = 212;
    const REFUND_SETTLED = 213;
    const PENDING = 300;
    const PROCESSING_ASYNC = 301;
    const REFUSED = 400;
    const DECLINED = 401;
    const REFERRED = 402;
    const NOT_ENOUGH_BALANCE = 403;
    const BLOCKED_CARD = 404;
    const RESTRICTED_CARD = 405;
    const INVALID_PAYMENT_INFORMATION = 410;
    const INVALID_CARD_NUMBER = 411;
    const EXPIRED_CARD = 412;
    const INVALID_SECURITY_CODE = 413;
    const INVALID_ADDRESS = 414;
    const FRAUD = 420;
    const FAILED_FRAUD_CHECK = 421;
    const ACQUIRER_SUSPECTED_FRAUD = 422;
    const ISSUER_SUSPECTED_FRAUD = 423;
    const INVALID_TRANSACTION = 430;
    const INVALID_AMOUNT = 431;
    const TRANSACTION_NOT_SUPPORTED = 432;
    const TRANSACTION_NOT_PERMITTED = 433;
    const PROCESSING_ISSUE = 450;
    const AUTHORIZATION_REVOKED = 451;
    const ACQUIRER_UNREACHABLE = 452;
    const ISSUER_UNREACHABLE = 453;
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
        self::CAPTURED => 'Captured',
        self::PARTIALLY_CAPTURED => 'Partially captured',
        self::SETTLED => 'Settled',
        self::VOIDED => 'Voided',
        self::REFUNDED => 'Refunded',
        self::PARTIALLY_REFUNDED => 'Partially refunded',
        self::REFUND_SETTLED => 'Refund settled',
        self::PENDING => 'Pending',
        self::PROCESSING_ASYNC => 'Processing asynchronously',
        self::REFUSED => 'Refused',
        self::DECLINED => 'Declined',
        self::REFERRED => 'Referred',
        self::NOT_ENOUGH_BALANCE => 'Not enough balance',
        self::BLOCKED_CARD => 'Card blocked',
        self::RESTRICTED_CARD => 'Card restricted',
        self::INVALID_PAYMENT_INFORMATION => 'Invalid payment information',
        self::INVALID_CARD_NUMBER => 'Invalid card number',
        self::EXPIRED_CARD => 'Expired card',
        self::INVALID_SECURITY_CODE => 'Invalid security code',
        self::INVALID_ADDRESS => 'Invalid address information',
        self::FRAUD => 'Fraud',
        self::FAILED_FRAUD_CHECK => 'Fraud check failed',
        self::ACQUIRER_SUSPECTED_FRAUD => 'Acquirer suspected fraud',
        self::ISSUER_SUSPECTED_FRAUD => 'Issuer suspected fraud',
        self::INVALID_TRANSACTION => 'Invalid transaction',
        self::INVALID_AMOUNT => 'Invalid amount',
        self::TRANSACTION_NOT_SUPPORTED => 'Transaction not supported',
        self::TRANSACTION_NOT_PERMITTED => 'Transaction not permitted',
        self::PROCESSING_ISSUE => 'Processing issue',
        self::AUTHORIZATION_REVOKED => 'Authorization revoked',
        self::ACQUIRER_UNREACHABLE => 'Acquirer unreachable',
        self::ISSUER_UNREACHABLE => 'Issuer unreachable',
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
        self::CAPTURED => 'The transaction was captured.',
        self::PARTIALLY_CAPTURED => 'The transaction was partially captured.',
        self::SETTLED => 'The transaction was settled.',
        self::VOIDED => 'The transaction has been voided.',
        self::REFUNDED => 'The transaction was refunded.',
        self::PARTIALLY_REFUNDED => 'The transaction was partially refunded.',
        self::REFUND_SETTLED => 'The refund was settled.',
        self::PENDING => 'The transaction is pending.',
        self::PROCESSING_ASYNC => 'The transaction is being completed asynchronously.',
        self::REFUSED => 'The transaction was refused.',
        self::DECLINED => 'The transaction was declined.',
        self::REFERRED => 'The transaction must be authorized by the the cardholder.',
        self::NOT_ENOUGH_BALANCE => 'Not enough balance/funds for the transaction to be processed.',
        self::BLOCKED_CARD => 'The card is blocked.',
        self::RESTRICTED_CARD => 'The card is restricted',
        self::INVALID_PAYMENT_INFORMATION => 'The payment information is not valid.',
        self::INVALID_CARD_NUMBER => 'The card number is not valid.',
        self::EXPIRED_CARD => 'The card is expired.',
        self::INVALID_SECURITY_CODE => 'The security code is not valid.',
        self::INVALID_ADDRESS => 'The address information is not valid.',
        self::FRAUD => 'The transaction was marked fraudulent.',
        self::FAILED_FRAUD_CHECK => 'The fraud check failed before attempting to process.',
        self::ACQUIRER_SUSPECTED_FRAUD => 'The transaction was marked fraudulent by the acquirer.',
        self::ISSUER_SUSPECTED_FRAUD => 'The transaction was marked fraudulent by the issuer.',
        self::INVALID_TRANSACTION => 'The transaction is not valid.',
        self::INVALID_AMOUNT => 'The amount provided is not valid.',
        self::TRANSACTION_NOT_SUPPORTED => 'The transaction is not supported.',
        self::TRANSACTION_NOT_PERMITTED => 'The transaction is not permitted.',
        self::PROCESSING_ISSUE => 'There was a processing issue.',
        self::AUTHORIZATION_REVOKED => 'The authorization for the transaction was revoked.',
        self::ACQUIRER_UNREACHABLE => 'The issuer is not reachable.',
        self::ISSUER_UNREACHABLE => 'The issuer is not reachable.',
        self::ERROR => 'An unexpected error occurred.',
        self::ACQUIRER_ERROR => 'There was an error while the acquirer was attempting to process the transaction.',
        self::ISSUER_ERROR => 'There was an error while the issuer was attempting to process the transaction.',
        self::UNKNOWN_ERROR => 'An unknown error occurred.',
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
}
