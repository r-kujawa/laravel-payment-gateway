<?php

namespace rkujawa\LaravelPaymentGateway\Contracts;

interface PaymentResponder
{
    /**
     * Maps data from the getWallet() response to the expected format.
     *
     * @return array|mixed
     */
    public function walletDetails();

    /**
     * Maps data from the getPaymentMethod() response to the expected format.
     *
     * @return array|mixed
     */
    public function paymentMethodDetails();

    /**
     * Maps data from the tokenizePaymentMethod() response to the expected format.
     *
     * @return array|mixed
     */
    public function tokenizationDetails();

    /**
     * Maps data from the updatePaymentMethod() response to the expected format.
     *
     * @return array|mixed
     */
    public function updatedPaymentMethodDetails();
    
    /**
     * Maps data from the removePaymentMethod() response to the expected format.
     *
     * @return array|mixed
     */
    public function extractionDetails();

    /**
     * Maps data from the authorize() response to the expected format.
     *
     * @return array|mixed
     */
    public function authorizationDetails();

    /**
     * Maps data from the capture() response to the expected format.
     *
     * @return array|mixed
     */
    public function captureDetails();

    /**
     * Maps data from the authorizeAndCapture() response to the expected format.
     *
     * @return array|mixed
     */
    public function authorizationAndCaptureDetails();

    /**
     * Maps data from the void() response to the expected format.
     *
     * @return array|mixed
     */
    public function voidDetails();

    /**
     * Maps data from the refund() response to the expected format.
     *
     * @return array|mixed
     */
    public function refundDetails();
}
