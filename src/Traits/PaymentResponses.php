<?php

namespace rkujawa\LaravelPaymentGateway\Traits;

trait PaymentResponses
{
    use ThrowsRuntimeException;

    /**
     * Maps data from the getWallet() response to the expected format.
     *
     * @return array|mixed
     */
    public function walletDetails()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Maps data from the getPaymentMethod() response to the expected format.
     *
     * @return array|mixed
     */
    public function paymentMethodDetails()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Maps data from the tokenizePaymentMethod() response to the expected format.
     *
     * @return array|mixed
     */
    public function tokenizationDetails()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Maps data from the updatePaymentMethod() response to the expected format.
     *
     * @return array|mixed
     */
    public function updatedPaymentMethodDetails()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }
    
    /**
     * Maps data from the removePaymentMethod() response to the expected format.
     *
     * @return array|mixed
     */
    public function extractionDetails()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Maps data from the authorize() response to the expected format.
     *
     * @return array|mixed
     */
    public function authorizationDetails()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Maps data from the capture() response to the expected format.
     *
     * @return array|mixed
     */
    public function captureDetails()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Maps data from the authorizeAndCapture() response to the expected format.
     *
     * @return array|mixed
     */
    public function authorizationAndCaptureDetails()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Maps data from the void() response to the expected format.
     *
     * @return array|mixed
     */
    public function voidDetails()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Maps data from the refund() response to the expected format.
     *
     * @return array|mixed
     */
    public function refundDetails()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }
}
