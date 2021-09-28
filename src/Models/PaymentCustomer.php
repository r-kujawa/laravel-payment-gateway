<?php

namespace rkujawa\LaravelPaymentGateway\Models;

//use App\Models\Order\Client; maybe we can replace this with a contract
use Illuminate\Database\Eloquent\Model;
use rkujawa\LaravelPaymentGateway\Contracts\Buyer;

class PaymentCustomer extends Model
{
    protected $fillable = ['client_id', 'payment_provider_id', 'token'];


    /**
     * Get the client that owns the current PaymentCustomer.
     * Note: If this were a package it would be polimorphic instead of harcoding the Client model.
     *
     * @return Buyer
     */
    /*public function client()
    {
        return $this->belongsTo(Client::class);
    }*/

    protected $client = [
        'model' => Buyer::class,// substitution contract
        'id' => 'id'
    ];

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
