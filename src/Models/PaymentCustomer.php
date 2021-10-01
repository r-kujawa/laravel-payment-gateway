<?php

namespace rkujawa\LaravelPaymentGateway\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use rkujawa\LaravelPaymentGateway\Database\Factories\PaymentCustomerFactory;
use rkujawa\LaravelPaymentGateway\Contracts\Buyer;

class PaymentCustomer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['provider_id', 'token'];
    protected $hidden = ['token'];

    protected $client = [
        'model' => Buyer::class,// substitution contract
        'id' => 'id'
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PaymentCustomerFactory::new();
    }

    public function client()
    {
        return $this->belongsTo($this->client['model'], 'client_id', $this->client['id']);
    }

    public function paymentProvider()
    {
        return $this->belongsTo(PaymentProvider::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public static function findByToken(string $token)
    {
        return self::where('token', $token)->first();
    }
}
