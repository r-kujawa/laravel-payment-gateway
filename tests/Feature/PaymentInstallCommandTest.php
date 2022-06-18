<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;
use rkujawa\LaravelPaymentGateway\Models\PaymentType;

class PaymentInstallCommandTest extends CommandTestCase
{
    /** @test */
    public function payment_install_command_publishes_assets_and_generates_the_test_payment_gateway()
    {
        $type = PaymentType::factory()->make();
        $provider = PaymentProvider::factory()->make();
        $merchant = PaymentMerchant::factory()->make();

        $this->artisan('payment:install')
            ->expectsQuestion($this->getArgumentQuestion('type'), $type->name)
            ->expectsQuestion($this->getOptionQuestion('display name', 'type', $type->name), $type->display_name)
            ->expectsQuestion($this->getOptionQuestion('slug', 'type', $type->name), $type->slug)
            ->expectsOutput($this->getMigrationOutput('type', $type->name))
            ->expectsConfirmation('Would you like to add another payment type?')
            ->expectsQuestion($this->getArgumentQuestion('provider'), $provider->name)
            ->expectsQuestion($this->getOptionQuestion('slug', 'provider', $provider->name), $provider->slug)
            ->expectsConfirmation('Would you like to add another payment provider?')
            ->expectsQuestion($this->getArgumentQuestion('merchant'), $merchant->name)
            ->expectsQuestion($this->getOptionQuestion('slug', 'merchant', $merchant->name), $merchant->slug)
            ->expectsChoice(
                "Which payment providers will the {$merchant->name} merchant be using? (First chosen will be default)",
                [$provider->slug],
                [$provider->slug]
            )
            ->expectsConfirmation('Would you like to add another payment merchant?');

        $this->assertTrue(file_exists(config_path('payment.php')));
        $this->assertTrue(file_exists(app_path('Services/Payment/TestPaymentGateway.php')));
    }
}
