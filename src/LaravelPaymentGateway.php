<?php

namespace rkujawa\LaravelPaymentGateway;

use rkujawa\LaravelPaymentGateway\Interfaces\PaymentGateway as PaymentGateway;

class LaravelPaymentGateway extends PaymentService implements PaymentGateway
{
    /**
     * @param \rkujawa\LaravelPaymentGateway\Interfaces\BillableContract $billable
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentManagerResponse
     */
    public function createPaymentMethod($billable, $data)
    {
        return $this->manager->createPaymentMethod($billable, $data);
    }

    /**
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @param array|mixed $data
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentManagerResponse
     */
    public function updatePaymentMethod($paymentMethod, $data)
    {
        return $this->manager->updatePaymentMethod($paymentMethod, $data);
    }
    
    /**
     * @param \rkujawa\LaravelPaymentGateway\Models\PaymentMethod $paymentMethod
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentManagerResponse
     */
    public function deletePaymentMethod($paymentMethod)
    {
        return $this->manager->deletePaymentMethod($paymentMethod);
    }

    /**
     * @param array|mixed $data
     * @param int $amount
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentProcessorResponse
     */
    public function charge($data, $amount)
    {
        return $this->processor->charge($data, $amount);
    }

    /**
     * TODO: Define the transaction tables and models.
     * 
     * @param mixed $paymentTransaction
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentProcessorResponse
     */
    public function void($paymentTransaction)
    {
        return $this->processor->void($paymentTransaction);
    }

    /**
     * TODO: Define the transaction tables and models.
     * 
     * @param mixed $paymentTransaction
     * @param int $amount
     * @return \rkujawa\LaravelPaymentGateway\Interfaces\PaymentProcessorResponse
     */
    public function refund($paymentTransaction, $amount)
    {
        return $this->processor->refund($paymentTransaction, $amount);
    }
}
