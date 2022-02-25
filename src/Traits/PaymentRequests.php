<?php

namespace rkujawa\LaravelPaymentGateway\Traits;

use rkujawa\LaravelPaymentGateway\Contracts\Billable;
use rkujawa\LaravelPaymentGateway\Models\PaymentMethod;
use rkujawa\LaravelPaymentGateway\Models\PaymentTransaction;
use rkujawa\LaravelPaymentGateway\Models\Wallet;

trait PaymentRequests
{
    use ThrowsRuntimeException;

    /**
     * Retrieve the wallet's details from the provider.
     *
     * @param \rkujawa\LaravelPaymentGateway\Models\Wallet $wallet
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function getWallet(Wallet $wallet)
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Retrieve the payment method's details from the provider.
     * 
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function getPaymentMethod(PaymentMethod $paymentMethod)
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Store the payment method details at the provider.
     * 
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Billable $billable
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function tokenizePaymentMethod(Billable $billable, $data)
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Update the payment method's details at the provider.
     * 
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function updatePaymentMethod(PaymentMethod $paymentMethod, $data)
    {
        $this->throwRuntimeException(__FUNCTION__);
    }
    
    /**
     * Extract the payment method from the provider.
     * 
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function removePaymentMethod(PaymentMethod $paymentMethod)
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Authorize a transaction.
     * 
     * @param array|mixed $data
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod|null $paymentMethod
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function authorize($data, PaymentMethod $paymentMethod = null)
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Capture a previously authorized transaction.
     * 
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentTransaction $transaction
     * @param array|mixed|null $data
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function capture(PaymentTransaction $transaction, $data = null)
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Request authorization for a transaction.
     * 
     * @param array|mixed $data
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod|null $paymentMethod
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function authorizeAndCapture($data, PaymentMethod $paymentMethod = null)
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Void a previously authorized transaction.
     * 
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentTransaction $paymentTransaction
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function void(PaymentTransaction $paymentTransaction)
    {
        $this->throwRuntimeException(__FUNCTION__);
    }

    /**
     * Refund a previously captured transaction.
     * 
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentTransaction $paymentTransaction
     * @param array|mixed|null
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function refund(PaymentTransaction $paymentTransaction, $data = null)
    {
        $this->throwRuntimeException(__FUNCTION__);
    }
}
