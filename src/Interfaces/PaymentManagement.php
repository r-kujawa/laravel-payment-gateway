<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces;

interface PaymentManagement
{
    /**
     * @param \rkujawa\LaravelPaymentGateway\Interfaces\BillableContract $billable
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\Response\PaymentManagerResponse
     */
    public function createPaymentMethod($billable, $data);

    /**
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\Response\PaymentManagerResponse
     */
    public function updatePaymentMethod($paymentMethod, $data);
    
    /**
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\Response\PaymentManagerResponse
     */
    public function deletePaymentMethod($paymentMethod);
}
