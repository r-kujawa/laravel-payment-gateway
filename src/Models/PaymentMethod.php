<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes;

    protected $fillable = ['payment_customer_id', 'fallback_id', 'token', 'first_name', 'last_name', 'last_digits', 'exp_month', 'exp_year', 'type', 'created_at'];

    protected $hidden = ['token'];

    public function getCardholderAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getExpirationDateAttribute()
    {
        return $this->getExpirationDate();
    }

    public function setFirstNameAttribute($value)
    {
        if (!is_null($value)) {
            $this->attributes['first_name'] = ucwords(strtolower(trim($value)));
        }
    }

    public function setLastNameAttribute($value)
    {
        if (!is_null($value)) {
            $this->attributes['last_name'] = ucwords(strtolower(trim($value)));
        }
    }

    public function getExpirationDate(string $seperator = '/')
    {
        return $this->exp_month . $seperator . $this->exp_year;
    }

    public function paymentCustomer()
    {
        return $this->belongsTo(PaymentCustomer::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Data::class, 'company_payment_method', 'payment_method_id', 'company_id')
            ->using(CompanyPaymentMethod::class)
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function fallback()
    {
        return $this->belongsTo(self::class);
    }

    public static function findByToken(string $token)
    {
        return self::where('token', $token)->first();
    }

    public function getContactData(): array
    {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ];
    }
}
