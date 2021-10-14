<?php

namespace rkujawa\LaravelPaymentGateway\Interfaces\Request;

interface PaymentManagementContract
{
    /**
     * @param \rkujawa\LaravelPaymentGateway\Interfaces\BillableContract $billable
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\Response\PaymentManagementContract
     */
    public function createPaymentMethod($billable, $data);

    /**
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\Response\PaymentManagementContract
     */
    public function updatePaymentMethod($paymentMethod, $data);
    
    /**
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\Response\PaymentManagementContract
     */
    public function deletePaymentMethod($paymentMethod);
}
