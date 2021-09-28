<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyPaymentMethod extends Pivot
{
    public $incrementing = true;

    protected $fillable = ['payment_method_id', 'company_id', 'is_primary'];

    protected $casts = [
        'is_primary' => 'boolean'
    ];
  
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function company()
    {
        return $this->belongsTo(Data::class, 'company_id', 'transnum');
    }
}
