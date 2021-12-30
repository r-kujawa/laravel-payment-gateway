<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use Exception;
use InvalidArgumentException;
use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;

class PaymentMerchantCommandTest extends TestCase
{
    /** @test */
    public function add_payment_merchant_command_makes_migration()
    {
        $paymentMerchant = PaymentMerchant::factory()->make();

        $this
            ->artisan('payment:add-merchant', [
                'merchant' => $paymentMerchant->name,
                '--slug' => $paymentMerchant->slug,
                'provider' => $paymentMerchant->provider->slug
            ])
            ->expectsOutput('The migration to add ' . $paymentMerchant->name . ' payment merchant has been generated.')
            ->expectsConfirmation('Would you like to run the migration?')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('payment_merchants', ['slug' => $paymentMerchant->slug]);

        $this->artisan('migrate');

        $this->assertDatabaseHas('payment_merchants', ['slug' => $paymentMerchant->slug]);
    }

    /** @test */
    public function add_payment_merchant_command_runs_migration_when_prompted()
    {
        $paymentMerchant = PaymentMerchant::factory()->make();

        $this
            ->artisan('payment:add-merchant', [
                'merchant' => $paymentMerchant->name,
                '--slug' => $paymentMerchant->slug,
                'provider' => $paymentMerchant->provider->slug
            ])
            ->expectsOutput('The migration to add ' . $paymentMerchant->name . ' payment merchant has been generated.')
            ->expectsConfirmation('Would you like to run the migration?', 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas('payment_merchants', ['slug' => $paymentMerchant->slug]);
    }

    /** @test */
    public function add_payment_merchant_command_fails_if_migration_already_exists()
    {
        $paymentMerchant = PaymentMerchant::factory()->make();

        $this
            ->artisan('payment:add-merchant', [
                'merchant' => $paymentMerchant->name,
                '--slug' => $paymentMerchant->slug,
                'provider' => $paymentMerchant->provider->slug
            ])
            ->expectsOutput('The migration to add ' . $paymentMerchant->name . ' payment merchant has been generated.')
            ->expectsConfirmation('Would you like to run the migration?')
            ->assertExitCode(0);

        try {
            $this
                ->artisan('payment:add-merchant', [
                    'merchant' => $paymentMerchant->name,
                    '--slug' => $paymentMerchant->slug,
                    'provider' => $paymentMerchant->provider->slug
                ])
                ->doesntExpectOutput('The migration to add ' . $paymentMerchant->name . ' payment merchant has been generated.')
                ->assertExitCode(0);
        } catch (Exception $e) {
            $this->assertEquals(InvalidArgumentException::class, get_class($e));
        }
    }
}
