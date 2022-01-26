<?php

namespace rkujawa\LaravelPaymentGateway\Tests;

use Exception;
use InvalidArgumentException;
use rkujawa\LaravelPaymentGateway\Models\PaymentMerchant;
use rkujawa\LaravelPaymentGateway\Models\PaymentProvider;

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
            ])
            ->expectsOutput('The migration to add ' . $paymentMerchant->name . ' payment merchant has been generated.')
            ->expectsConfirmation('Would you like to run the migration?')
            ->assertExitCode(0);

        try {
            $this
                ->artisan('payment:add-merchant', [
                    'merchant' => $paymentMerchant->name,
                    '--slug' => $paymentMerchant->slug,
                ])
                ->doesntExpectOutput('The migration to add ' . $paymentMerchant->name . ' payment merchant has been generated.')
                ->assertExitCode(0);
        } catch (Exception $e) {
            $this->assertEquals(InvalidArgumentException::class, get_class($e));
        }
    }

    /** @test */
    public function add_payment_merchant_command_will_not_prompt_to_run_migration_when_passing_skip_migration_option()
    {
        $paymentMerchant = PaymentMerchant::factory()->make();

        $this
            ->artisan('payment:add-merchant', [
                'merchant' => $paymentMerchant->name,
                '--slug' => $paymentMerchant->slug,
                '--skip-migration' => true,
            ])
            ->expectsOutput('The migration to add '.$paymentMerchant->name.' payment merchant has been generated.')
            ->assertExitCode(0);
        
        $this->artisan('migrate');

        $this->assertDatabaseHas('payment_merchants', ['slug' => $paymentMerchant->slug]);
    }

    /** @test */
    public function add_payment_merchant_command_will_prompt_for_missing_arguments()
    {
        $paymentMerchant = PaymentMerchant::factory()->make();

        $this
            ->artisan('payment:add-merchant')
            ->expectsQuestion('What merchant would you like to add?', $paymentMerchant->name)
            ->expectsQuestion('What slug would you like to use for the '.$paymentMerchant->name.' merchant?', $paymentMerchant->slug)
            ->expectsOutput('The migration to add ' . $paymentMerchant->name . ' payment merchant has been generated.')
            ->expectsConfirmation('Would you like to run the migration?', 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas('payment_merchants', ['slug' => $paymentMerchant->slug]);
    }

    /** @test */
    public function add_payment_merchant_command_will_prompt_to_choose_related_providers_if_exists_in_config()
    {
        $paymentProvider = PaymentProvider::factory()->create();
        $paymentMerchant = PaymentMerchant::factory()->make();

        config(['payment.providers' => [$paymentProvider->slug]]);

        $this
            ->artisan('payment:add-merchant', [
                'merchant' => $paymentMerchant->name,
                '--slug' => $paymentMerchant->slug,
            ])
            ->expectsChoice('Which payment providers will the '.$paymentMerchant->name.' merchant be using? (First chosen will be default)', $paymentProvider->slug, config('payment.providers'))
            ->expectsOutput('The migration to add ' . $paymentMerchant->name . ' payment merchant has been generated.')
            ->expectsConfirmation('Would you like to run the migration?', 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas('payment_merchants', ['slug' => $paymentMerchant->slug]);
    }

    /** @test */
    public function add_payment_merchant_command_will_not_prompt_for_providers_if_skip_provider_flag_is_provided()
    {
        $paymentProvider = PaymentProvider::factory()->create();
        $paymentMerchant = PaymentMerchant::factory()->make();

        config(['payment.providers' => [$paymentProvider->slug]]);

        $this
            ->artisan('payment:add-merchant', [
                'merchant' => $paymentMerchant->name,
                '--slug' => $paymentMerchant->slug,
                '--skip-provider' => true
            ])
            ->expectsOutput('The migration to add ' . $paymentMerchant->name . ' payment merchant has been generated.')
            ->expectsConfirmation('Would you like to run the migration?', 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas('payment_merchants', ['slug' => $paymentMerchant->slug]);
    }
}
