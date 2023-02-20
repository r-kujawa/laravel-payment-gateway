<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;

class InstallCommandTest extends TestCase
{
    /** @test */
    public function install_command_publishes_migration_and_generates_config_with_single_provider_and_merchant()
    {
        $provider = PaymentProvider::factory()->make();
        $merchant = PaymentMerchant::factory()->make();

        $this->artisan('payment:install')
            ->expectsOutput('Fake payment gateway generated successfully!')
            ->expectsQuestion('What payment provider would you like to add?', $provider->name)
            ->expectsQuestion('How would you like to identify the ' . $provider->name . ' payment provider?', $provider->id)
            ->expectsOutput($provider->name . ' payment gateway generated successfully!')
            ->expectsConfirmation('Would you like to add another payment provider?', 'no')
            ->expectsQuestion('What payment merchant would you like to add?', $merchant->name)
            ->expectsQuestion('How would you like to identify the ' . $merchant->name . ' payment merchant?', $merchant->id)
            ->expectsConfirmation('Would you like to add another payment merchant?', 'no')
            ->expectsOutput('The payment config has been successfully generated.');

        $this->artisan('config:cache');

        $payment = config('payment');

        $this->assertEquals($provider->id, $payment['defaults']['provider']);
        $this->assertEquals($merchant->id, $payment['defaults']['merchant']);
        $this->assertEquals($provider->name, $payment['providers'][$provider->id]['name']);
        $this->assertEquals($merchant->name, $payment['merchants'][$merchant->id]['name']);
        $this->assertNotNull($payment['merchants'][$merchant->id]['providers'][$provider->id] ?? null);
    }
}
