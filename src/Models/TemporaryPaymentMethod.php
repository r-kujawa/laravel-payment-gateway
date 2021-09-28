<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryPaymentMethod extends Model
{
    protected $primaryKey = 'payment_profile_token';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'customer_profile_token',
        'payment_profile_token',
        'first_name',
        'last_name',
        'last_digits',
        'expiration_date',
        'type',
        'created_at',
        'dismiss'
    ];

    const DISMISS = [
        'default' => 0,                 // Unprocessed
        'success' => 1,                 // Succesfully processed
        'multiple_clients' => 2,        // Belongs to multiple clients
        'duplicate_card_customer' => 3, // Duplicate card in customer_profile scope
        'duplicate_card_client' => 4,   // Duplicate card in client scope
        'no_relation' => 5,             // Doesn't relate to any order/client
    ];

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = empty($value) ? null : ucwords(strtolower($value));
    }

    public function getFirstNameAttribute()
    {
        return preg_match('/[^a-z .\'-]+$/', $this->attributes['first_name']) ? null : $this->attributes['first_name'];
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = empty($value) ? null : ucwords(strtolower($value));
    }

    public function getLastNameAttribute()
    {
        return preg_match('/[^a-z .\'-]+$/', $this->attributes['last_name']) ? null : $this->attributes['last_name'];
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = ($value === 'AmericanExpress') ? 'amex' : strtolower($value);
    }

    public function relations()
    {
        return $this->hasMany(TemporaryPaymentMethodRelation::class, 'payment_profile_token');
    }

    public function orders()
    {
        return $this->belongsToMany(Data::class, 'temporary_payment_method_relations', 'payment_profile_token', 'order_id', 'payment_profile_token', 'transnum');
    }

    public function parentOrders()
    {
        return $this->belongsToMany(Data::class, 'temporary_payment_method_relations', 'payment_profile_token', 'parent_id', 'payment_profile_token', 'transnum');
    }

    public function owners()
    {
        return $this->belongsToMany(Client::class, 'temporary_payment_method_relations', 'payment_profile_token', 'owner_id', 'payment_profile_token', 'id');
    }
}
