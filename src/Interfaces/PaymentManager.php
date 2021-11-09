<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

interface PaymentManager
{
    /**
     * @param \rkujawa\LaravelPaymentGateway\Interfaces\BillableContract $billable
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentManagerResponse
     */
    public function createPaymentMethod($billable, $data);

    /**
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentManagerResponse
     */
    public function updatePaymentMethod($paymentMethod, $data);
    
    /**
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentManagerResponse
     */
    public function deletePaymentMethod($paymentMethod);
}
