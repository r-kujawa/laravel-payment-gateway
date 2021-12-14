<?php

namespace rkujawa\LaravelPaymentGateway\Contracts;

interface PaymentManager
{
    /**
     * @param \rkujawa\LaravelPaymentGateway\Contracts\BillableContract $billable
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentManagerResponse
     */
    public function createPaymentMethod($billable, $data);

    /**
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentManagerResponse
     */
    public function updatePaymentMethod($paymentMethod, $data);
    
    /**
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentManagerResponse
     */
    public function deletePaymentMethod($paymentMethod);

    /**
     * @param string $token
     * @return \rkujawa\LaravelPaymentGateway\Contracts\PaymentManagerResponse
     */
    public function getPaymentMethod($token);
}
