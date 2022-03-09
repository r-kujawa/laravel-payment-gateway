<?php

namespace rkujawa\LaravelPaymentGateway\Contracts;

use rkujawa\LaravelPaymentGateway\Contracts\Billable;
use rkujawa\LaravelPaymentGateway\Models\PaymentMethod;
use rkujawa\LaravelPaymentGateway\Models\PaymentTransaction;
use rkujawa\LaravelPaymentGateway\Models\Wallet;

interface PaymentRequestor
{
    /**
     * Retrieve the wallet's details from the provider.
     *
     * @param \rkujawa\LaravelPaymentGateway\Models\Wallet $wallet
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function getWallet(Wallet $wallet);

    /**
     * Retrieve the payment method's details from the provider.
     * 
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function getPaymentMethod(PaymentMethod $paymentMethod);

    /**
     * Store the payment method details at the provider.
     * 
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Billable $billable
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function tokenizePaymentMethod(Billable $billable, $data);

    /**
     * Update the payment method's details at the provider.
     * 
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function updatePaymentMethod(PaymentMethod $paymentMethod, $data);
    
    /**
     * Delete the payment method at the provider.
     * 
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function deletePaymentMethod(PaymentMethod $paymentMethod);

    /**
     * Authorize a transaction.
     * 
     * @param array|mixed $data
     * @param \rkujawa\LaravelPaymentGateway\Contracts\Billable|null $billable
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function authorize($data, Billable $billable = null);

    /**
     * Capture a previously authorized transaction.
     * 
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentTransaction $transaction
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function capture(PaymentTransaction $transaction, $data = []);

    /**
     * Void a previously authorized transaction.
     * 
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentTransaction $paymentTransaction
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function void(PaymentTransaction $paymentTransaction, $data = []);

    /**
     * Refund a previously captured transaction.
     * 
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentTransaction $paymentTransaction
     * @param array|mixed
     * @return \rkujawa\LaravelPaymentGateway\PaymentResponse
     */
    public function refund(PaymentTransaction $paymentTransaction, $data = []);
}
