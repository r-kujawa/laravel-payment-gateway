<?php

namespace rkujawa\LaravelPaymentGateway\Contracts;

interface PaymentResponder
{
    /**
     * Maps details from the getWallet() response to the expected format.
     *
     * @return array|mixed
     */
    public function walletDetails();

    /**
     * Maps details from the getPaymentMethod() response to the expected format.
     *
     * @return array|mixed
     */
    public function paymentMethodDetails();

    /**
     * Maps details from the tokenizePaymentMethod() response to the expected format.
     *
     * @return array|mixed
     */
    public function tokenizationDetails();

    /**
     * Maps details from the updatePaymentMethod() response to the expected format.
     *
     * @return array|mixed
     */
    public function updatedPaymentMethodDetails();
    
    /**
     * Maps details from the removePaymentMethod() response to the expected format.
     *
     * @return array|mixed
     */
    public function extractionDetails();

    /**
     * Maps details from the authorize() response to the expected format.
     *
     * @return array|mixed
     */
    public function authorizationDetails();

    /**
     * Maps details from the capture() response to the expected format.
     *
     * @return array|mixed
     */
    public function captureDetails();

    /**
     * Maps details from the authorizeAndCapture() response to the expected format.
     *
     * @return array|mixed
     */
    public function authorizationAndCaptureDetails();

    /**
     * Maps details from the void() response to the expected format.
     *
     * @return array|mixed
     */
    public function voidDetails();

    /**
     * Maps details from the refund() response to the expected format.
     *
     * @return array|mixed
     */
    public function refundDetails();
}
