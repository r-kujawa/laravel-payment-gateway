<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRefund extends Model
{
    protected $fillable = ['gateway_refund_id', 'transaction_id', 'amount', 'payload', 'created_at'];
}