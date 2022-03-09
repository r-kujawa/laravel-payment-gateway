<?php

namespace rkujawa\LaravelPaymentGateway\Traits;

trait PaymentResponses
{
    use ThrowsRuntimeException;

    /**
     * Maps details from the getWallet() response to the expected format.
     *
     * @return array|mixed
     */
    public function getWalletResponse()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Maps details from the getPaymentMethod() response to the expected format.
     *
     * @return array|mixed
     */
    public function getPaymentMethodResponse()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Maps details from the tokenizePaymentMethod() response to the expected format.
     *
     * @return array|mixed
     */
    public function tokenizePaymentMethodResponse()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Maps details from the updatePaymentMethod() response to the expected format.
     *
     * @return array|mixed
     */
    public function updatePaymentMethodResponse()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }
    
    /**
     * Maps details from the deletePaymentMethod() response to the expected format.
     *
     * @return array|mixed
     */
    public function deletePaymentMethodResponse()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Maps details from the authorize() response to the expected format.
     *
     * @return array|mixed
     */
    public function authorizeResponse()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Maps details from the capture() response to the expected format.
     *
     * @return array|mixed
     */
    public function captureResponse()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Maps details from the void() response to the expected format.
     *
     * @return array|mixed
     */
    public function voidResponse()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Maps details from the refund() response to the expected format.
     *
     * @return array|mixed
     */
    public function refundResponse()
    {
        $this->throwRuntimeException(__FUNCTION__);
    }
}
