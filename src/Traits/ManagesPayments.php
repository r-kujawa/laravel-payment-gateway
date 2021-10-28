<?php

namespace rkujawa\LaravelPaymentGateway\Traits;

trait ManagesPayments
{
    /**
     * @param \rkujawa\LaravelPaymentGateway\Interfaces\BillableContract $billable
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentManagerResponse
     */
    public function createPaymentMethod($billable, $data)
    {
        return $this->gateway->createPaymentMethod($billable, $data);
    }

    /**
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentManagerResponse
     */
    public function updatePaymentMethod($paymentMethod, $data)
    {
        return $this->gateway->updatePaymentMethod($paymentMethod, $data);
    }
    
    /**
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentManagerResponse
     */
    public function deletePaymentMethod($paymentMethod)
    {
        return $this->gateway->deletePaymentMethod($paymentMethod);
    }
}
