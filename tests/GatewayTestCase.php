<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use rkujawa\LaravelPaymentGateway\Contracts\Billable;
use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
use rkujawa\LaravelPaymentGateway\Models\PaymentMethod;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;
use rkujawa\LaravelPaymentGateway\Models\PaymentTransaction;
use rkujawa\LaravelPaymentGateway\Models\Wallet;
use rkujawa\LaravelPaymentGateway\PaymentRequest;
use rkujawa\LaravelPaymentGateway\PaymentResponse;
use rkujawa\LaravelPaymentGateway\PaymentStatus;
use rkujawa\LaravelPaymentGateway\Traits\Billable as BillableTrait;

abstract class GatewayTestCase extends TestCase
{
    protected $provider;
    protected $merchant;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');

        Schema::create('users', function ($table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });

        $this->provider = PaymentProvider::create([
            'name' => 'Test',
            'slug' => 'test',
        ]);

        $this->merchant = PaymentMerchant::create([
            'name' => 'Tester',
            'slug' => 'tester',
            'display_name' => 'Tester',
        ]);

        $this->merchant->providers()->attach($this->provider->id, ['is_default' => true]);

        config([
            'payment.defaults.provider' => $this->provider->slug,
            'payment.defaults.merchant' => $this->merchant->slug,
            'payment.providers.' . $this->provider->slug . '.path' => TestPaymentGateway::class,
        ]);
    }
}

class TestPaymentGateway extends PaymentRequest
{
    public function getWallet(Wallet $wallet)
    {
        return new TestPaymentResponse([]);
    }

    public function getPaymentMethod(PaymentMethod $paymentMethod)
    {
        return new TestPaymentResponse([]);
    }

    public function tokenizePaymentMethod(Billable $billable, $data)
    {
        return new TestPaymentResponse([]);
    }

    public function updatePaymentMethod(PaymentMethod $paymentMethod, $data)
    {
        return new TestPaymentResponse([]);
    }
    
    public function removePaymentMethod(PaymentMethod $paymentMethod)
    {
        return new TestPaymentResponse([]);
    }

    public function authorize($data, Billable $billable = null)
    {
        return new TestPaymentResponse([]);
    }

    public function capture(PaymentTransaction $transaction, $data = [])
    {
        return new TestPaymentResponse([]);
    }

    public function void(PaymentTransaction $paymentTransaction, $data =[])
    {
        return new TestPaymentResponse([]);
    }

    public function refund(PaymentTransaction $paymentTransaction, $data = [])
    {
        return new TestPaymentResponse([]);
    }
}

class TestPaymentResponse extends PaymentResponse
{
    public function walletDetails()
    {
        return [
            'requestMethod' => $this->requestMethod,
        ];
    }

    public function paymentMethodDetails()
    {
        return [
            'requestMethod' => $this->requestMethod,
        ];
    }

    public function tokenizationDetails()
    {
        return [
            'requestMethod' => $this->requestMethod,
        ];
    }

    public function updatedPaymentMethodDetails()
    {
        return [
            'requestMethod' => $this->requestMethod,
        ];
    }

    public function extractionDetails()
    {
        return [
            'requestMethod' => $this->requestMethod,
        ];
    }

    public function authorizationDetails()
    {
        return [
            'requestMethod' => $this->requestMethod,
        ];
    }

    public function captureDetails()
    {
        return [
            'requestMethod' => $this->requestMethod,
        ];
    }

    public function voidDetails()
    {
        return [
            'requestMethod' => $this->requestMethod,
        ];
    }

    public function refundDetails()
    {
        return [
            'requestMethod' => $this->requestMethod,
        ];
    }

    public function getStatusCode()
    {
        return PaymentStatus::AUTHORIZED;
    }
}

class User extends Model implements Billable
{
    use BillableTrait,
        HasFactory;
    
    protected static function newFactory()
    {
        return UserFactory::new();
    }
}

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'email' => $this->faker->email(),
            'password' => $this->faker->password(),
        ];
    }
}
