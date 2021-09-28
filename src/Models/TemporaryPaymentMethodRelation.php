<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryPaymentMethodRelation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'payment_profile_token',
        'order_id',
        'owner_id',
        'is_primary',
        'simulated'
    ];

    protected $casts = [
        'is_primary' => 'boolean'
    ];

    public function temporaryPaymentMethod()
    {
        return $this->belongsTo(TemporaryPaymentMethod::class, 'payment_profile_token', 'payment_profile_token');
    }
}
