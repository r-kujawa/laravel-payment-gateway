<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;

class InstallCommandTest extends TestCase
{
    /** @test */
    public function install_command_publishes_migration_and_generates_config()
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
    }
}